<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos téléchargements — Commande {{ $order->order_number }}</title>
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
                                            Bamako Supermarché • Produits Numériques
                                        </div>
                                    </td>
                                    <td align="right">
                                        <span style="display: inline-block; background-color: #16A34A; color: #FFFFFF; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase;">
                                            ✓ Payé
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
                                Nous vous remercions pour votre achat ! Votre paiement pour la commande <strong>{{ $order->order_number }}</strong> a bien été validé avec succès.
                            </p>

                            <!-- Boîte d'accès aux fichiers numériques -->
                            <div style="background-color: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 18px; margin-bottom: 25px;">
                                <div style="font-size: 14px; font-weight: 700; color: #166534; margin-bottom: 4px;">
                                    ⚡ Vos fichiers sont prêts au téléchargement
                                </div>
                                <div style="font-size: 12px; color: #15803D;">
                                    Cliquez sur les boutons ci-dessous pour télécharger vos documents et supports immédiatement :
                                </div>
                            </div>

                            <!-- Liste des fichiers téléchargeables -->
                            <div style="margin-bottom: 25px;">
                                @php
                                    $hasFiles = false;
                                @endphp
                                @foreach($order->items as $item)
                                    @if($item->isDigital() && $item->product)
                                        @if($item->product->files && $item->product->files->count() > 0)
                                            @php $hasFiles = true; @endphp
                                            @foreach($item->product->files as $file)
                                                <div style="background-color: #FAFAFA; border: 1px solid #E4E4E7; border-radius: 8px; padding: 16px; margin-bottom: 12px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td>
                                                                <div style="font-size: 14px; font-weight: 700; color: #111111; margin-bottom: 3px;">
                                                                    {{ $item->product->name }}
                                                                </div>
                                                                <div style="font-size: 12px; color: #6B7280;">
                                                                    📄 {{ $file->file_name }} &bull; {{ $file->formatted_file_size }} ({{ strtoupper($file->file_extension) }})
                                                                </div>
                                                                @if($item->product->download_limit)
                                                                    <div style="font-size: 11px; color: #9CA3AF; margin-top: 4px;">
                                                                        Limite : jusqu'à {{ $item->product->download_limit }} téléchargements
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-top: 12px;">
                                                                <a 
                                                                    href="{{ route('digital.download', ['orderNumber' => $order->order_number, 'fileId' => $file->id]) }}"
                                                                    target="_blank"
                                                                    style="display: inline-block; background-color: #E31E24; color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; padding: 10px 20px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                                                                >
                                                                    📥 Télécharger le fichier
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            @endforeach
                                        @endif

                                        {{-- Lien d'accès externe éventuel (formation, vidéo privée) --}}
                                        @if($item->product->external_access_url)
                                            <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; border-radius: 8px; padding: 16px; margin-bottom: 12px;">
                                                <div style="font-size: 14px; font-weight: 700; color: #1E40AF; margin-bottom: 4px;">
                                                    🔗 Accès en ligne : {{ $item->product->name }}
                                                </div>
                                                <div style="font-size: 12px; color: #2563EB; margin-bottom: 10px;">
                                                    Ce contenu est accessible directement via la plateforme dédiée :
                                                </div>
                                                <a 
                                                    href="{{ $item->product->external_access_url }}"
                                                    target="_blank"
                                                    style="display: inline-block; background-color: #2563EB; color: #FFFFFF; font-size: 13px; font-weight: 700; text-decoration: none; padding: 8px 18px; border-radius: 6px;"
                                                >
                                                    Accéder au contenu en ligne &rarr;
                                                </a>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                            <!-- Espace client -->
                            <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 16px; margin-bottom: 25px; text-align: center;">
                                <div style="font-size: 13px; font-weight: 600; color: #1E293B; margin-bottom: 4px;">
                                    Retrouvez vos achats à tout moment
                                </div>
                                <div style="font-size: 12px; color: #64748B; margin-bottom: 12px;">
                                    Vos fichiers restent accessibles dans votre espace client BKO SU.
                                </div>
                                <a 
                                    href="{{ route('account.downloads') }}"
                                    target="_blank"
                                    style="display: inline-block; background-color: #111111; color: #FFFFFF; font-size: 12px; font-weight: 600; text-decoration: none; padding: 8px 16px; border-radius: 6px;"
                                >
                                    Ouvrir mon espace Téléchargements
                                </a>
                            </div>

                            <!-- Facture Acquittée & Reçu Officiel -->
                            <div style="background-color: #F9FAFB; border: 1px solid #E4E4E7; border-radius: 8px; padding: 18px; margin-top: 25px; margin-bottom: 20px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; margin-bottom: 12px;">
                                    <tr>
                                        <td>
                                            <div style="font-size: 13px; font-weight: 800; color: #111111;">FACTURE ACQUITTÉE N° FACT-{{ $order->order_number }}</div>
                                            <div style="color: #6B7280; font-size: 11px; margin-top: 2px;">Émise le : {{ $order->created_at->format('d/m/Y') }}</div>
                                        </td>
                                        <td align="right">
                                            <span style="display: inline-block; background-color: #DCFCE7; color: #15803D; font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 4px; text-transform: uppercase;">
                                                ✓ Réglée
                                            </span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Détail des articles -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; border-top: 1px solid #E5E7EB; border-bottom: 1px solid #E5E7EB; margin-top: 8px; margin-bottom: 12px;">
                                    <tr style="background-color: #F3F4F6;">
                                        <th align="left" style="padding: 8px 6px; font-size: 11px; text-transform: uppercase; color: #6B7280;">Désignation</th>
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

                                <!-- Totaux & Paiement -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 12px; color: #4B5563;">
                                    <tr>
                                        <td style="padding-bottom: 4px;">Sous-total HT :</td>
                                        <td align="right" style="padding-bottom: 4px;">{{ $order->formatted_subtotal }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-bottom: 4px;">Frais de livraison :</td>
                                        <td align="right" style="padding-bottom: 4px;">{{ $order->delivery_fee > 0 ? $order->formatted_delivery_fee : 'Offerte (0 FCFA)' }}</td>
                                    </tr>
                                    <tr style="font-size: 14px; font-weight: 800; color: #E31E24;">
                                        <td style="padding-top: 6px; border-top: 1px solid #E5E7EB;">MONTANT TOTAL RÉGLÉ :</td>
                                        <td align="right" style="padding-top: 6px; border-top: 1px solid #E5E7EB;">{{ $order->formatted_total }}</td>
                                    </tr>
                                </table>

                                <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #D1D5DB; font-size: 11px; color: #166534;">
                                    ✓ Règlement reçu via <strong>{{ $order->payment_method_label }}</strong>
                                    @if($order->orange_money_transaction_id)
                                        (Réf. Transaction : {{ $order->orange_money_transaction_id }})
                                    @endif
                                </div>
                            </div>

                            <!-- Bouton Télécharger Facture Officielle -->
                            <div style="text-align: center; margin-bottom: 25px;">
                                <a 
                                    href="{{ route('order.invoice', ['orderNumber' => $order->order_number, 'token' => $order->tracking_token]) }}"
                                    target="_blank"
                                    style="display: inline-block; background-color: #111111; color: #FFFFFF; font-size: 12px; font-weight: 700; text-decoration: none; padding: 10px 22px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);"
                                >
                                    📄 Télécharger / Imprimer ma facture officielle (PDF)
                                </a>
                            </div>

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
                                Ce lien est strictement personnel et sécurisé pour votre commande.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
