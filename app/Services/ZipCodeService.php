<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ZipCodeService
{
    private const CACHE_TTL = 3600; // 1 hora
    private const API_TIMEOUT = 10; // 10 segundos
    private const RETRY_ATTEMPTS = 2;
    private const RETRY_DELAY = 1000; // 1 segundo
    
    /**
     * Busca um endereço pelo CEP na API do ViaCEP.
     *
     * @param string $zipCode O CEP a ser consultado.
     * @return array|null Retorna um array com os dados do endereço ou null se o CEP não for encontrado.
     * @throws \Exception Se houver um erro de conexão ou outro erro inesperado.
     */
    public function getAddressByZipCode(string $zipCode): ?array
    {
        $cep = $this->sanitizeZipCode($zipCode);
        
        if (!$this->isValidZipCode($cep)) {
            Log::info("CEP inválido fornecido: {$zipCode}");
            return null;
        }

        try {
            // Cache para evitar requests desnecessários
            return Cache::remember("zipcode_{$cep}", self::CACHE_TTL, function () use ($cep) {
                return $this->fetchFromApi($cep);
            });
        } catch (\Exception $e) {
            Log::error("Erro ao buscar CEP {$cep}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove todos os caracteres não numéricos do CEP.
     *
     * @param string $zipCode
     * @return string
     */
    private function sanitizeZipCode(string $zipCode): string
    {
        return preg_replace('/[^0-9]/', '', $zipCode);
    }

    /**
     * Valida se o CEP possui exatamente 8 dígitos.
     *
     * @param string $cep
     * @return bool
     */
    private function isValidZipCode(string $cep): bool
    {
        return strlen($cep) === 8 && ctype_digit($cep);
    }

    /**
     * Faz a requisição para a API do ViaCEP.
     *
     * @param string $cep
     * @return array|null
     * @throws \Exception
     */
    private function fetchFromApi(string $cep): ?array
    {
      $response = Http::timeout(self::API_TIMEOUT)
            ->retry(self::RETRY_ATTEMPTS, self::RETRY_DELAY)
            ->get("https://viacep.com.br/ws/{$cep}/json/");
      
      $response->throw();
      $data = $response->json();

      if (isset($data['erro']) && $data['erro'] === true) {
            return null;
      }

      return $this->formatResponse($data, $cep);
    }

    /**
     * Formata a resposta da API para o padrão esperado pela aplicação.
     *
     * @param array $data
     * @param string $cep
     * @return array
     */
    private function formatResponse(array $data, string $cep): array
    {
        return [
            'street' => trim($data['logradouro'] ?? ''),
            'district' => trim($data['bairro'] ?? ''),
            'city' => trim($data['localidade'] ?? ''),
            'state' => strtoupper(trim($data['uf'] ?? '')),
            'zip_code' => $this->formatZipCode($cep),
        ];
    }

    /**
     * Formata o CEP no padrão 00000-000.
     *
     * @param string $cep
     * @return string
     */
    private function formatZipCode(string $cep): string
    {
        return substr($cep, 0, 5) . '-' . substr($cep, 5);
    }

    /**
     * Limpa o cache de um CEP específico.
     *
     * @param string $zipCode
     * @return bool
     */
    public function clearCache(string $zipCode): bool
    {
        $cep = $this->sanitizeZipCode($zipCode);
        
        if (!$this->isValidZipCode($cep)) {
            return false;
        }

        return Cache::forget("zipcode_{$cep}");
    }

    /**
     * Limpa todo o cache de CEPs.
     *
     * @return void
     */
    public function clearAllCache(): void
    {
        // Se usando Redis/Memcached, você pode implementar uma limpeza mais eficiente
        Cache::flush(); // Atenção: isso limpa TODO o cache da aplicação
    }

    /**
     * Verifica se um CEP está em cache.
     *
     * @param string $zipCode
     * @return bool
     */
    public function isCached(string $zipCode): bool
    {
        $cep = $this->sanitizeZipCode($zipCode);
        
        if (!$this->isValidZipCode($cep)) {
            return false;
        }

        return Cache::has("zipcode_{$cep}");
    }
}