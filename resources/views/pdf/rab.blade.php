@php
    function romanMonth($month)
    {
        $map = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];
        return $map[$month] ?? '';
    }

    $month = now()->month;
    $year = now()->year;

    $noRAB = 'RAB/' . romanMonth($month) . '/' . $year;
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>RAB {{ $rab['project']->project_name ?? '' }}</title>

    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: "Roboto", sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .info-table {
            width: 100%;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .info-table td {
            padding: 2px 4px;
        }

        table.main {
            width: 100%;
            border-collapse: collapse;
        }

        table.main th,
        table.main td {
            border: 1px solid #000;
            padding: 4px;
        }

        table.main th {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .no-border td {
            border: none !important;
        }

        .section-title {
            font-weight: bold;
            background: #eee;
        }

        .signature {
            margin-top: 40px;
            width: 100%;
        }

        .signature td {
            text-align: center;
        }

        .user {
            font-weight: bold;
            text-transform: uppercase;
            padding-top: 80px;
        }
    </style>
</head>

<body>

    <h1>RENCANA ANGGARAN BIAYA (RAB)</h1>

    <!-- INFO -->
    <table class="info-table">
        <tr>
            <td>Provinsi</td>
            <td>:</td>
            <td>{{ config('app.domicile.province') }}</td>
            <td>No. RAB</td>
            <td>:</td>
            <td>{{ $noRAB }}</td>
        </tr>
        <tr>
            <td>Kabupaten</td>
            <td>:</td>
            <td>{{ config('app.domicile.regency') }}</td>
            <td>Program</td>
            <td>:</td>
            <td>{{ $rab['project']->project_name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kecamatan</td>
            <td>:</td>
            <td>{{ config('app.domicile.district') }}</td>
            <td>Jenis Kegiatan</td>
            <td>:</td>
            <td>{{ $rab['project']->project_name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Desa</td>
            <td>:</td>
            <td>{{ config('app.domicile.village') }}</td>
            <td>Volume</td>
            <td>:</td>
            <td>{{ $rab['project']->volume ?? '-' }} {{ $rab['project']->unit ?? '-' }}</td>
        </tr>

    </table>

    <!-- TABLE -->
    <table class="main">
        <thead>
            <tr>
                <th>Uraian</th>
                <th>Kebutuhan</th>
                <th>Satuan</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>

            <!-- ================= BAHAN ================= -->
            <tr class="section-title">
                <td colspan="5">1. BAHAN</td>
            </tr>

            @php $subtotalMaterial = 0; @endphp
            @foreach ($rab['recap_material'] as $index => $item)
                @php $subtotalMaterial += (float) str_replace('.', '', $item['total']); @endphp
                <tr>
                    <td style="padding-left: 20px;">1.{{ $index + 1 }} {{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $item['unit'] }}</td>
                    <td class="text-right">{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="text-right" colspan="4"><strong>Subtotal 1)</strong></td>
                <td class="text-right"><strong>{{ number_format($subtotalMaterial, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- ================= ALAT ================= -->
            <tr class="section-title">
                <td colspan="5">2. ALAT</td>
            </tr>

            @php $subtotalTool = 0; @endphp
            @foreach ($rab['recap_tool'] as $index => $item)
                @php $subtotalTool += (float) str_replace('.', '', $item['total']); @endphp
                <tr>
                    <td style="padding-left: 20px;">2.{{ $index + 1 }} {{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $item['unit'] }}</td>
                    <td class="text-right">{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="text-right" colspan="4"><strong>Subtotal 2)</strong></td>
                <td class="text-right"><strong>{{ number_format($subtotalTool, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- ================= UPAH ================= -->
            <tr class="section-title">
                <td colspan="5">3. UPAH</td>
            </tr>

            @php $subtotalWage = 0; @endphp
            @foreach ($rab['recap_wage'] as $index => $item)
                @php $subtotalWage += (float) str_replace('.', '', $item['total']); @endphp
                <tr>
                    <td style="padding-left: 20px;">3.{{ $index + 1 }} {{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $item['unit'] }}</td>
                    <td class="text-right">{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="text-right" colspan="4"><strong>Subtotal 3)</strong></td>
                <td class="text-right"><strong>{{ number_format($subtotalWage, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- ================= OPERASIONAL ================= -->
            <tr class="section-title">
                <td colspan="5">4. BIAYA OPERASIONAL</td>
            </tr>

            @php $subtotalOperational = 0; @endphp
            @foreach ($rab['operational'] as $index => $item)
                @php $subtotalOperational += (float) str_replace('.', '', $item['total']); @endphp
                <tr>
                    <td style="padding-left: 20px;">4.{{ $index + 1 }} {{ $item['name'] }}</td>
                    <td>{{ $item['volume'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td class="text-right">{{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr>
                <td class="text-right" colspan="4"><strong>Subtotal 4)</strong></td>
                <td class="text-right"><strong>{{ number_format($subtotalOperational, 0, ',', '.') }}</strong></td>
            </tr>

            <!-- GRAND TOTAL -->
            <tr>
                <td class="text-center" colspan="4"><strong>JUMLAH TOTAL</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($rab['summary']['grand_total'], 0, ',', '.') }}</strong>
                </td>
            </tr>

        </tbody>
    </table>

    <!-- SIGNATURE -->
    <table class="signature no-border">
        <tr>
            <td>Mengetahui</td>
            <td>Dibuat Oleh</td>
        </tr>
        <tr>
            <td>Kepala Desa {{ config('app.domicile.village') }}</td>
            <td>Tim Pelaksana Kegiatan</td>
        </tr>
        <tr>
            <td class="user"> {{ $rab['approver']->name ?? '(.........................)' }}</td>
            <td class="user">{{ $rab['chairman'] ?? '(.........................)' }}</td>
        </tr>
    </table>

</body>

</html>
