{{-- Detalles del Pedido - Template para Email --}}
<div
    style="max-width: 800px; margin: 0 auto; background-color: #ffffff; font-family: Arial, sans-serif; line-height: 1.5;">

    {{-- Header --}}
    <div style="background-color: #f9f9f9; padding: 24px; border-bottom: 1px solid #e5e7eb;">
        <h2 style="margin: 0; font-size: 20px; font-weight: 600; color: #111827;">
            Detalles del Pedido #{{ $pedido->id }}
        </h2>
    </div>

    {{-- Content --}}
    <div style="padding: 24px;">

        {{-- Datos del Usuario --}}
        <div style="background-color: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <h3 style="margin: 0 0 12px 0; font-weight: 600; color: #111827;">
                Datos del Cliente
            </h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Nombre:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->name ?? '' }}</div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Email:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->email ?? '' }}</div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">CUIT:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->cuit ?? '' }}</div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Teléfono:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->telefono ?? '' }}</div>
                    </div>
                </div>
                <div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Dirección:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->direccion ?? '' }}</div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Localidad:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->localidad ?? '' }}</div>
                    </div>
                    <div style="margin-bottom: 8px;">
                        <span style="color: #6b7280; font-size: 14px;">Provincia:</span>
                        <div style="font-weight: 500; color: #111827;">{{ $pedido->user->provincia ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Información del Pedido y Resumen de Costos --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">

            {{-- Información del Pedido --}}
            <div style="background-color: #f9fafb; padding: 16px; border-radius: 8px;">
                <h3 style="margin: 0 0 12px 0; font-weight: 600; color: #111827;">
                    Información del Pedido
                </h3>
                <div style="margin-bottom: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Tipo de Entrega:</span>
                        <span style="font-weight: 500;">{{ $pedido->tipo_entrega }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Tipo de Pago:</span>
                        <span style="font-weight: 500;">{{ $pedido->forma_pago }}</span>
                    </div>
                    @if($pedido->mensaje)
                        <div style="padding-top: 8px;">
                            <span style="color: #6b7280;">Mensaje:</span>
                            <p
                                style="margin: 4px 0 0 0; padding: 8px; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; color: #374151;">
                                {{ $pedido->mensaje }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Resumen de Costos --}}
            <div style="background-color: #f9fafb; padding: 16px; border-radius: 8px;">
                <h3 style="margin: 0 0 12px 0; font-weight: 600; color: #111827;">
                    Resumen de Costos
                </h3>
                <div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Subtotal:</span>
                        <span style="font-weight: 500;">${{ number_format($pedido->subtotal, 2, '.', ',') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">Descuento:</span>
                        <span
                            style="font-weight: 500; color: #10b981;">-${{ number_format($pedido->descuento, 2, '.', ',') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                        <span style="color: #6b7280;">IVA:</span>
                        <span style="font-weight: 500;">${{ number_format($pedido->iva, 2, '.', ',') }}</span>
                    </div>
                    <div style="border-top: 1px solid #d1d5db; padding-top: 8px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 18px; font-weight: 600; color: #111827;">Total:</span>
                            <span
                                style="font-size: 18px; font-weight: 600; color: #111827;">${{ number_format($pedido->total, 2, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Productos --}}
        <div>
            <h3 style="margin: 0 0 12px 0; font-weight: 600; color: #111827;">Productos</h3>

            <table
                style="width: 100%; border-collapse: collapse; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                <thead style="background-color: #f9fafb;">
                    <tr>
                        <th
                            style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                            Código
                        </th>
                        <th
                            style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                            Nombre
                        </th>
                        <th
                            style="padding: 12px 16px; text-align: center; font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                            Cantidad
                        </th>
                        <th
                            style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                            Precio
                        </th>
                        <th
                            style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 500; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">
                            Subtotal
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedido->productos as $index => $producto)
                        <tr style="border-top: 1px solid #e5e7eb;">
                            <td style="padding: 12px 16px; font-size: 14px; color: #111827;">
                                {{ $producto->producto->code ?? '' }}
                            </td>
                            <td style="padding: 12px 16px; font-size: 14px; color: #111827;">
                                {{ $producto->producto->name ?? '' }}
                            </td>
                            <td style="padding: 12px 16px; text-align: center; font-size: 14px; color: #111827;">
                                {{ $producto->cantidad }}
                            </td>
                            <td style="padding: 12px 16px; text-align: right; font-size: 14px; color: #111827;">
                                ${{ $producto->precio_unitario }}
                            </td>
                            <td
                                style="padding: 12px 16px; text-align: right; font-size: 14px; font-weight: 500; color: #111827;">
                                ${{ $producto->cantidad * $producto->precio_unitario }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>