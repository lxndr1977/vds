<!DOCTYPE html>
<html lang="pt-BR">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Coreografias - {{ $record->school->name }}</title>

   <style>
      @media print {
         * {
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
         }

         .page-break {
            page-break-before: always;
         }

         .no-print {
            display: none;
         }
      }

      body {
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
         line-height: 1.5;
         color: #1f2937;
         margin: 0;
         padding: 20mm;
         font-size: 12px;
         background: #fff;
      }

      .header {
         text-align: center;
         margin-bottom: 30px;
         padding-bottom: 20px;
         border-bottom: 3px solid #111827;
      }

      .header h1 {
         color: #111827;
         margin: 0 0 8px 0;
         font-size: 24px;
         font-weight: 700;
         letter-spacing: 1px;
      }

      .header h2 {
         margin: 0 0 8px 0;
         font-size: 18px;
         font-weight: 400;
         color: #4b5563;
      }

      .header .choreography-name {
         margin: 8px 0 0 0;
         font-size: 16px;
         font-weight: 600;
         color: #111827;
         background: #f3f4f6;
         padding: 8px 16px;
         border-radius: 6px;
         display: inline-block;
      }

      .section {
         margin-bottom: 25px;
         border-radius: 8px;
         overflow: hidden;
         box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }

      .section-header {
         background: #f9fafb;
         padding: 12px 20px;
         border-bottom: 2px solid #e5e7eb;
         font-weight: 600;
         font-size: 14px;
         color: #111827;
         letter-spacing: 0.5px;
      }

      .section-content {
         padding: 20px;
         border: 1px solid #e5e7eb;
         border-top: none;
      }

      .school-grid {
         display: grid;
         grid-template-columns: 1fr 1fr;
         gap: 20px;
         margin-bottom: 15px;
      }

      .field-group {
         margin-bottom: 12px;
      }

      .field-label {
         font-weight: 600;
         margin-bottom: 4px;
         display: block;
         font-size: 11px;
         color: #374151;
         text-transform: uppercase;
         letter-spacing: 0.5px;
      }

      .field-value {
         font-size: 13px;
         color: #111827;
         padding: 4px 0;
      }

      .choreography-details-grid {
         display: grid;
         grid-template-columns: repeat(3, 1fr);
         gap: 15px;
         margin-bottom: 20px;
         padding: 15px;
         background: #f8fafc;
         border-radius: 6px;
         border: 1px solid #e2e8f0;
      }

      .detail-item {
         text-align: center;
      }

      .detail-label {
         font-weight: 600;
         font-size: 10px;
         color: #64748b;
         margin-bottom: 4px;
         text-transform: uppercase;
         letter-spacing: 0.5px;
      }

      .detail-value {
         font-size: 12px;
         color: #111827;
         font-weight: 500;
      }

      .choreography-info-grid {
         display: grid;
         grid-template-columns: 1fr 1fr;
         gap: 20px;
         margin-bottom: 20px;
      }

      .participants-table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 25px;
         border-radius: 6px;
         overflow: hidden;
         box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      }

      .participants-table th {
         background: #111827;
         color: #fff;
         padding: 12px 10px;
         font-weight: 600;
         text-align: left;
         font-size: 11px;
         text-transform: uppercase;
         letter-spacing: 0.5px;
      }

      .participants-table td {
         padding: 10px;
         border-bottom: 1px solid #e5e7eb;
         font-size: 12px;
         color: #111827;
      }

      .participants-table tbody tr:nth-child(even) {
         background: #f8fafc;
      }

      .participants-table tbody tr:hover {
         background: #f1f5f9;
      }

      .table-title {
         font-weight: 700;
         font-size: 14px;
         margin-bottom: 10px;
         color: #111827;
         text-transform: uppercase;
         letter-spacing: 0.5px;
         padding-bottom: 6px;
         border-bottom: 2px solid #111827;
      }

      .no-data {
         text-align: center;
         padding: 40px 20px;
         color: #6b7280;
         background: #f9fafb;
         border-radius: 8px;
         border: 1px solid #e5e7eb;
      }

      .print-controls {
         margin-bottom: 20px;
         text-align: center;
         padding: 15px;
         background: #f3f4f6;
         border-radius: 8px;
      }

      .print-btn {
         background: #111827;
         color: white;
         padding: 10px 20px;
         border: none;
         border-radius: 6px;
         cursor: pointer;
         font-size: 12px;
         margin: 0 8px;
         font-weight: 500;
         transition: background 0.2s;
      }

      .print-btn:hover {
         background: #374151;
      }

      .print-btn.secondary {
         background: #6b7280;
      }

      .print-btn.secondary:hover {
         background: #4b5563;
      }

      @media screen {
         body {
            max-width: 210mm;
            margin: 0 auto;
            background: #f9fafb;
            padding: 20px;
         }

         .page-content {
            background: white;
            padding: 20mm;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
         }
      }

      @media print {
         @page {
            size: A4;
            padding-top: 25mm !important;
            padding-bottom: 20mm !important;
            padding-left: 20mm !important;
            padding-right: 20mm !important;
         }

         body {
            max-width: none;
            background: white;
            padding: 0;
            /* Remove o padding do body */
            font-size: 11px;
         }

         .page-content {
            background: white;
            padding: 0;
            box-shadow: none;
            margin-bottom: 0;
         }

         .print-controls {
            display: none;
         }
      }
   </style>
</head>

<body>
   {{-- Controles de Impressão --}}
   <div class="print-controls no-print">
      <button class="print-btn" onclick="window.print()">🖨️ Imprimir</button>
      <button class="print-btn secondary" onclick="window.close()">✕ Fechar</button>
   </div>

   {{-- Cada Coreografia em uma Página Separada --}}
   @forelse($record->school->choreographies as $index => $choreography)
      @if ($index > 0)
         <div class="page-break"></div>
      @endif

      <div class="page-content">
         {{-- Cabeçalho da Página --}}
         <div class="header">
            <h1>VEM DANÇAR SUDAMÉRICA 2025</h1>
            <h2>{{ $record->school->name }}</h2>
            <div class="choreography-name">{{ $choreography->name }}</div>
         </div>

         {{-- Dados da Escola --}}
         <div class="section">
            <div class="section-header">
               DADOS DA ESCOLA/GRUPO/CIA
            </div>
            <div class="section-content">
               <div class="school-grid">
                  <div class="field-group">
                     <label class="field-label">Nome</label>
                     <div class="field-value">{{ $record->school->name }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Responsável</label>
                     <div class="field-value">{{ $record->school->responsible_name }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Email</label>
                     <div class="field-value">{{ $record->school->responsible_email }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">WhatsApp</label>
                     <div class="field-value">{{ $record->school->responsible_phone }}</div>
                  </div>
               </div>
               <div class="field-group">
                  <label class="field-label">Endereço Completo</label>
                  <div class="field-value">
                     {{ $record->school->street }}, {{ $record->school->number }}
                     @if ($record->school->complement)
                        , {{ $record->school->complement }}
                     @endif,
                     {{ $record->school->district }}, {{ $record->school->city }}/{{ $record->school->state }}
                  </div>
               </div>
            </div>
         </div>

         {{-- Detalhes da Coreografia --}}
         <div class="section">
            <div class="section-header">
               DETALHES DA COREOGRAFIA
            </div>
            <div class="section-content">
               <div class="choreography-details-grid">
                  <div class="detail-item">
                     <div class="detail-label">Formação</div>
                     <div class="detail-value">{{ $choreography->choreographyType->name }}</div>
                  </div>
                  <div class="detail-item">
                     <div class="detail-label">Categoria</div>
                     <div class="detail-value">{{ $choreography->choreographyCategory->name }}</div>
                  </div>
                  <div class="detail-item">
                     <div class="detail-label">Modalidade</div>
                     <div class="detail-value">{{ $choreography->danceStyle->name }}</div>
                  </div>
               </div>

               <div class="choreography-info-grid">
                  <div class="field-group">
                     <label class="field-label">Música</label>
                     <div class="field-value">{{ $choreography->music }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Compositor</label>
                     <div class="field-value">{{ $choreography->music_composer }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Duração</label>
                     <div class="field-value">{{ $choreography->duration }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Total de Participantes</label>
                     <div class="field-value">
                        {{ $choreography->choreographers->count() + $choreography->dancers->count() }} pessoas</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Projeto Social</label>
                     <div class="field-value">{{ $choreography->is_social_project ? 'Sim' : 'Não' }}</div>
                  </div>
                  <div class="field-group">
                     <label class="field-label">Projeto Universitário</label>
                     <div class="field-value">{{ $choreography->is_university_project ? 'Sim' : 'Não' }}</div>
                  </div>
               </div>
            </div>
         </div>

         {{-- Equipe Diretiva --}}
         @if ($record->school->members->count() > 0)
            <div class="table-title">Equipe Diretiva ({{ $record->school->members->count() }})</div>
            <table class="participants-table">
               <thead>
                  <tr>
                     <th width="8%">#</th>
                     <th width="60%">Nome Completo</th>
                     <th width="32%">Função</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($record->school->members as $member)
                     <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->memberType->name ?? 'Não definido' }}</td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         @endif

         {{-- Coreógrafos da Coreografia --}}
         @if ($choreography->choreographers->count() > 0)
            <div class="table-title">Coreógrafos - {{ $choreography->name }}
               ({{ $choreography->choreographers->count() }})
            </div>
            <table class="participants-table">
               <thead>
                  <tr>
                     <th width="8%">#</th>
                     <th width="60%">Nome Completo</th>
                     <th width="32%">Tipo</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($choreography->choreographers as $choreographer)
                     <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $choreographer->name }}</td>
                        <td>{{ $choreographer->choreographer_types }}</td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         @endif

         {{-- Bailarinos da Coreografia --}}
         @if ($choreography->dancers->count() > 0)
            <div class="table-title">Bailarinos - {{ $choreography->name }} ({{ $choreography->dancers->count() }})
            </div>
            <table class="participants-table">
               <thead>
                  <tr>
                     <th width="8%">#</th>
                     <th width="65%">Nome Completo</th>
                     <th width="27%">Data de Nascimento</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($choreography->dancers as $dancer)
                     <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dancer->name }}</td>
                        <td>{{ $dancer->birth_date }}</td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         @endif
      </div>

   @empty
      <div class="page-content">
         <div class="header">
            <h1>VEM DANÇAR SUDAMÉRICA 2025</h1>
            <h2>{{ $record->school->name }}</h2>
         </div>
         <div class="no-data">
            <h2>Nenhuma Coreografia Cadastrada</h2>
            <p>Esta inscrição ainda não possui coreografias cadastradas.</p>
         </div>
      </div>
   @endforelse

</body>

</html>
