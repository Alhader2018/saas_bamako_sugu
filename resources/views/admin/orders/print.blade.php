<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bon de Livraison — {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #111;
            margin: 0;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #E31E24;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #E31E24;
        }
        .box {
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border-bottom: 1px solid #eee;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background: #f9f9f9;
            font-size: 11px;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .total-box {
            width: 250px;
            margin-left: auto;
            border-top: 2px solid #111;
            padding-top: 8px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #E31E24; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            🖨️ Imprimer ce bon de livraison
        </button>
    </div>

    <div class="header">
        <div>
            <div class="title">BKO SU — Bamako Supermarché</div>
            <div style="color: #666; font-size: 11px; margin-top: 3px;">Supermarché en ligne • Bamako, Mali • Tél : +223 70 00 00 00</div>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 16px; font-weight: bold;">COMMANDE #{{ $order->order_number }}</div>
            <div style="color: #666; font-size: 11px;">Date : {{ $order->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div class="box" style="flex: 1;">
            <strong style="display: block; margin-bottom: 6px; font-size: 12px; text-transform: uppercase; color: #666;">Destinataire :</strong>
            <div style="font-size: 14px; font-weight: bold;">{{ $order->customer_full_name }}</div>
            <div style="font-size: 14px; color: #E31E24; font-weight: bold; margin-top: 3px;">Tél : {{ $order->customer_phone }}</div>
            @if($order->customer_email)
                <div style="color: #666; font-size: 12px;">{{ $order->customer_email }}</div>
            @endif
        </div>
        <div class="box" style="flex: 1;">
            <strong style="display: block; margin-bottom: 6px; font-size: 12px; text-transform: uppercase; color: #666;">Livraison Bamako :</strong>
            <div><strong>Quartier :</strong> {{ $order->neighborhood ?: 'Bamako' }}</div>
            <div><strong>Adresse / Repère :</strong> {{ $order->address ?: 'Non précisé' }}</div>
            @if($order->delivery_notes)
                <div style="margin-top: 4px; background: #fff8e1; padding: 4px;"><strong>Note livreur :</strong> {{ $order->delivery_notes }}</div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-right">Prix</th>
                <th style="text-align: center;">Qté</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td><strong>{{ $item->product_name }}</strong></td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td class="text-right"><strong>{{ number_format($item->total, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
            <span>Sous-total :</span>
            <span>{{ $order->formatted_subtotal }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
            <span>Frais de livraison :</span>
            <span>{{ $order->formatted_delivery_fee }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; color: #E31E24; margin-top: 6px;">
            <span>MONTANT TOTAL :</span>
            <span>{{ $order->formatted_total }}</span>
        </div>
        <div style="margin-top: 10px; font-size: 11px; color: #555;">
            Règlement : <strong>{{ $order->payment_method === 'orange_money' ? 'Orange Money (' . strtoupper($order->payment_status) . ')' : 'ESPÈCES À ENCAISSER À LA LIVRAISON' }}</strong>
        </div>
    </div>
</body>
</html>
