<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture FACT-{{ $order->order_number }} — BKO SU</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #1F2937;
            background-color: #F9FAFB;
            margin: 0;
            padding: 24px;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 36px 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #E31E24;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .brand-name {
            font-size: 24px;
            font-weight: 800;
            color: #111111;
            letter-spacing: -0.5px;
        }
        .brand-name span {
            color: #E31E24;
        }
        .brand-subtitle {
            font-size: 11px;
            color: #6B7280;
            margin-top: 3px;
        }
        .invoice-title-block {
            text-align: right;
        }
        .invoice-title {
            font-size: 20px;
            font-weight: 800;
            color: #111111;
        }
        .invoice-ref {
            font-size: 12px;
            color: #6B7280;
            margin-top: 4px;
        }
        .status-badge {
            display: inline-block;
            margin-top: 6px;
            background-color: #DCFCE7;
            color: #15803D;
            border: 1px solid #BBF7D0;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .meta-grid {
            display: flex;
            gap: 24px;
            margin-bottom: 28px;
        }
        .meta-col {
            flex: 1;
            background-color: #F9FAFB;
            border: 1px solid #F3F4F6;
            border-radius: 8px;
            padding: 16px;
        }
        .meta-col-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #9CA3AF;
            margin-bottom: 8px;
        }
        .meta-text {
            font-size: 12px;
            line-height: 1.5;
            color: #374151;
        }
        .meta-text strong {
            color: #111111;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table.items-table th {
            background-color: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6B7280;
            text-align: left;
        }
        table.items-table td {
            border-bottom: 1px solid #F3F4F6;
            padding: 12px;
            font-size: 12px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 28px;
        }
        .totals-box {
            width: 280px;
            background-color: #FAFAFA;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 14px 16px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            padding: 4px 0;
            color: #4B5563;
        }
        .totals-row.grand-total {
            border-top: 1px solid #E5E7EB;
            margin-top: 6px;
            padding-top: 8px;
            font-size: 14px;
            font-weight: 800;
            color: #E31E24;
        }
        .payment-info {
            background-color: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 8px;
            padding: 14px 16px;
            font-size: 12px;
            color: #166534;
            margin-bottom: 28px;
        }
        .footer-legal {
            border-top: 1px solid #E5E7EB;
            padding-top: 18px;
            font-size: 11px;
            color: #9CA3AF;
            text-align: center;
            line-height: 1.5;
        }
        .action-bar {
            max-width: 800px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-primary {
            background-color: #E31E24;
            color: #FFFFFF;
            border: none;
        }
        .btn-primary:hover {
            background-color: #C9171D;
        }
        .btn-outline {
            background-color: #FFFFFF;
            color: #374151;
            border: 1px solid #D1D5DB;
        }
        .btn-outline:hover {
            background-color: #F9FAFB;
        }
        @media print {
            body {
                background: #FFFFFF;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .invoice-card {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Barre d'actions non imprimée -->
    <div class="action-bar">
        <a href="{{ route('home') }}" class="btn btn-outline">
            &larr; Retour à l'accueil
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Imprimer ou Enregistrer en PDF
        </button>
    </div>

    <!-- Carte Facture Officielle -->
    <div class="invoice-card">
        
        <!-- En-tête -->
        <div class="header">
            <div>
                <div class="brand-name">BKO <span>SU</span></div>
                <div class="brand-subtitle">Bamako Supermarché • Tout Bamako dans un seul panier</div>
                <div style="font-size: 11px; color: #6B7280; margin-top: 4px;">
                    ACI 2000, Bamako, Mali • Tél: +223 70 00 00 00 • contact@bamakosugu.com
                </div>
            </div>
            <div class="invoice-title-block">
                <div class="invoice-title">FACTURE</div>
                <div class="invoice-ref">N° FACT-{{ $order->order_number }}</div>
                <div>
                    @if($order->isPaid())
                        <span class="status-badge">✓ Facture Acquittée</span>
                    @else
                        <span style="display: inline-block; margin-top: 6px; background: #FEF3C7; color: #92400E; border: 1px solid #FDE68A; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 9999px; text-transform: uppercase;">
                            En attente de paiement
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations Émetteur / Destinataire -->
        <div class="meta-grid">
            <div class="meta-col">
                <div class="meta-col-title">Facturé à (Client) :</div>
                <div class="meta-text">
                    <strong>{{ $order->customer_full_name }}</strong><br>
                    Tél : {{ $order->customer_phone }}<br>
                    @if($order->customer_email)
                        Email : {{ $order->customer_email }}<br>
                    @endif
                    @if($order->hasPhysicalItems())
                        Adresse : {{ $order->address }}<br>
                        Quartier : {{ $order->neighborhood }}, Bamako
                    @else
                        Type : <em>Achat numérique / Téléchargement immédiat</em>
                    @endif
                </div>
            </div>

            <div class="meta-col">
                <div class="meta-col-title">Détails de la Facture :</div>
                <div class="meta-text">
                    <strong>Date d'émission :</strong> {{ $order->created_at->format('d/m/Y') }}<br>
                    <strong>Commande associée :</strong> {{ $order->order_number }}<br>
                    <strong>Moyen de règlement :</strong> {{ $order->payment_method_label }}<br>
                    @if($order->orange_money_transaction_id)
                        <strong>Réf. Transaction :</strong> {{ $order->orange_money_transaction_id }}<br>
                    @endif
                    <strong>Nature :</strong> {{ $order->order_nature_label }}
                </div>
            </div>
        </div>

        <!-- Tableau des Lignes Facturées -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Désignation de l'article</th>
                    <th class="text-center">Nature</th>
                    <th class="text-right">Prix Unitaire</th>
                    <th class="text-center">Qté</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong style="color: #111111;">{{ $item->product_name }}</strong>
                            @if($item->isDigital() && $item->product)
                                <div style="font-size: 11px; color: #16A34A; margin-top: 2px;">
                                    ✓ Accès dématérialisé disponible
                                </div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($item->isDigital())
                                <span style="display: inline-block; background: #ECFDF5; color: #047857; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">
                                    Numérique
                                </span>
                            @else
                                <span style="display: inline-block; background: #F3F4F6; color: #4B5563; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 4px;">
                                    Physique
                                </span>
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right"><strong style="color: #111111;">{{ number_format($item->total, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totals-wrapper">
            <div class="totals-box">
                <div class="totals-row">
                    <span>Sous-total HT :</span>
                    <span>{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="totals-row">
                    <span>Frais de livraison :</span>
                    <span>{{ $order->delivery_fee > 0 ? $order->formatted_delivery_fee : 'Gratuit (0 FCFA)' }}</span>
                </div>
                <div class="totals-row grand-total">
                    <span>TOTAL RÉGLÉ TTC :</span>
                    <span>{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>

        <!-- Encadré Confirmation de Paiement -->
        @if($order->isPaid())
            <div class="payment-info">
                <strong>✓ Règlement vérifié et encaissé :</strong> 
                Le montant total de <strong>{{ $order->formatted_total }}</strong> a été réglé avec succès via 
                <strong>{{ $order->payment_method_label }}</strong>
                @if($order->orange_money_transaction_id)
                    (Transaction n° {{ $order->orange_money_transaction_id }})
                @endif
                le {{ $order->updated_at->format('d/m/Y à H:i') }}. Cette facture tient lieu de reçu officiel acquitté.
            </div>
        @endif

        <!-- Mentions Légales -->
        <div class="footer-legal">
            <div>BKO SU — Plateforme E-Commerce Bamako Supermarché • www.bamakosugu.com</div>
            <div>Pour toute assistance concernant cette facture, contactez notre service client au +223 70 00 00 00 ou par WhatsApp.</div>
            <div style="margin-top: 4px; font-size: 10px;">Document édité informatiquement, valable sans signature manuelle.</div>
        </div>

    </div>

</body>
</html>
