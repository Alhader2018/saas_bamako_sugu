<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Facture — Commande {{ $order->order_number }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F4F4F5; color: #1C1C1C;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #F4F4F5; padding: 30px 10px;">
        <tr>
            <td align="center">
                <!-- Conteneur principal (max 600px) -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #FFFFFF; border-radius: 12px; border: 1px solid #E4E4E7; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    
                    <!-- En-tête avec marque BKO SU -->
                    <tr>
                        <td style="background-color: #111111; padding: 24px 30px; text-align: left; border-bottom: 3px solid #E31E24;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <div style="font-size: 22px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.5px;">
                                            BKO <span style="color: #E31E24;">SU</span>
                                        </div>
                                        <div style="font-size: 11px; color: #9CA3AF; margin-top: 2px;">
                                            Bamako Supermarché • Tout Bamako dans un seul panier
                                        </div>
                                    </td>
                                    <td align="right">
                                        <span style="display: inline-block; background-color: #16A34A; color: #FFFFFF; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase;">
                                            ✓ Facture Acquittée
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Contenu du message -->
                    <tr>
                        <td style="padding: 30px;">
                            <h1 style="font-size: 19px; font-weight: 700; color: #111111; margin-top: 0; margin-bottom: 8px;">
                                Bonjour {{ $order->customer_first_name }},
                            </h1>
                            <p style="font-size: 14px; line-height: 1.6; color: #4B5563; margin-top: 0; margin-bottom: 20px;">
                                Nous vous confirmons la bonne réception de votre règlement pour la commande <strong>{{ $order->order_number }}</strong>. Vous trouverez ci-dessous votre facture détaillée.
                            </p>

                            <!-- Boîte Facture Officielle -->
                            <div style="background-color: #F9FAFB; border: 1px solid #E4E4E7; border-radius: 8px; padding: 18px; margin-bottom: 25px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; margin-bottom: 12px;">
                                    <tr>
                                        <td>
                                            <div style="font-size: 14px; font-weight: 800; color: #111111;">FACTURE N° FACT-{{ $order->order_number }}</div>
                                            <div style="color: #6B7280; font-size: 11px; margin-top: 2px;">Date : {{ $order->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td align="right">
                                            <div style="font-size: 11px; color: #6B7280;">Mode de paiement :</div>
                                            <div style="font-size: 12px; font-weight: 700; color: #111111;">{{ $order->payment_method_label }}</div>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Tableau des articles -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB; margin-top: 8px; margin-bottom: 12px;">
                                    <tr style="background-color: #F3F4F6;">
                                        <th align="left" style="padding: 8px 6px; font-size: 11px; text-transform: uppercase; color: #6B7280;">Article</th>
                                        <th align="center" style="padding: 8px 6px; font-size: 11px; text-transform: uppercase; color: #6B7280;">Qté</th>
                                        <th align="right" style="padding: 8px 6px; font-size: 11px; text-transform: uppercase; color: #6B7280;">Total</th>
                                    </tr>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td style="padding: 8px 6px; border-bottom: 1px solid #F3F4F6; color: #111111;">
                                                <strong>{{ $item->product_name }}</strong>
                                            </td>
                                            <td align="center" style="padding: 8px 6px; border-bottom: 1px solid #F3F4F6; color: #4B5563;">
                                                {{ $item->quantity }}
                                            </td>
                                            <td align="right" style="padding: 8px 6px; border-bottom: 1px solid #F3F4F6; font-weight: 700; color: #111111;">
                                                {{ number_format($item->total, 0, ',', ' ') }} FCFA
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>

                                <!-- Totaux -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; color: #4B5563;">
                                    <tr>
                                        <td style="padding-bottom: 4px;">Sous-total :</td>
                                        <td align="right" style="padding-bottom: 4px;">{{ $order->formatted_subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 4px;">Livraison à Bamako :</td>
                                        <td align="right" style="padding-bottom: 4px;">{{ $order->delivery_fee > 0 ? $order->formatted_delivery_fee : 'Offerte (0 FCFA)' }}</td>
                                    </tr>
                                    <tr style="font-size: 14px; font-weight: 800; color: #E31E24;">
                                        <td style="padding-top: 6px; border-top: 1px solid #E5E7EB;">TOTAL RÉGLÉ :</td>
                                        <td align="right" style="padding-top: 6px; border-top: 1px solid #E5E7EB;">{{ $order->formatted_total }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Bouton Télécharger / Imprimer la Facture Officielle -->
                            <div style="text-align: center; margin-bottom: 25px;">
                                <a 
                                    href="{{ route('order.invoice', ['orderNumber' => $order->order_number, 'token' => $order->tracking_token]) }}"
                                    target="_blank"
                                    style="display: inline-block; background-color: #111111; color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; padding: 12px 24px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                                >
                                    🖨️ Télécharger / Imprimer ma facture (PDF)
                                </a>
                            </div>

                            <!-- Informations de livraison si commande physique -->
                            @if($order->hasPhysicalItems())
                                <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; margin-bottom: 25px; font-size: 12px;">
                                    <div style="font-weight: 700; color: #1E293B; margin-bottom: 4px;">
                                        📦 Adresse de livraison à Bamako
                                    </div>
                                    <div style="color: #64748B;">
                                        {{ $order->address }} &bull; Quartier : {{ $order->neighborhood }}<br>
                                        Contact : {{ $order->customer_phone }}
                                    </div>
                                </div>
                            @endif

                        </td>
                    </tr>

                    <!-- Pied de page de l'email -->
                    <tr>
                        <td style="background-color: #F4F4F5; padding: 20px 30px; text-align: center; font-size: 11px; color: #71717A; border-top: 1px solid #E4E4E7;">
                            <div style="font-weight: 600; color: #3F3F46; margin-bottom: 4px;">
                                BKO SU — Bamako Supermarché
                            </div>
                            <div>
                                ACI 2000, Bamako, Mali • Contact WhatsApp / Téléphone : +223 70 00 00 00
                            </div>
                            <div style="margin-top: 8px; color: #A1A1AA;">
                                Ce reçu de facture tient lieu de preuve de paiement officiel.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
