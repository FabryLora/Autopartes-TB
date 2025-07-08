import { router, usePage } from '@inertiajs/react';
import { useState } from 'react';
import toast from 'react-hot-toast';
import { useCart } from 'react-use-cart';

export default function MispedidosRow({ pedido }) {
    const { addItem } = useCart();
    const { subproductos, auth } = usePage().props;
    const [pedidoView, setPedidoView] = useState(false);

    const { user } = auth;

    const [detalleView, setDetalleView] = useState(false);

    const recomprar = () => {
        router.post(
            route('recomprar'),
            {
                pedido_id: pedido?.id,
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Productos añadidos al carrito');
                },
                onError: (error) => {
                    toast.error(error.response.data.message || 'Error al añadir productos al carrito');
                },
            },
        );
    };

    return (
        <>
            {detalleView && (
                <div
                    className="fixed top-0 left-0 z-50 flex h-screen w-screen items-center justify-center bg-black/50"
                    onClick={() => setDetalleView(false)}
                >
                    <div className="fixed inset-0 z-50 flex items-center justify-center">
                        {/* Overlay */}

                        {/* Modal */}
                        <div className="relative mx-4 max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-lg bg-white shadow-xl">
                            {/* Header */}
                            <div className="flex items-center justify-between border-b border-gray-200 p-6">
                                <h2 className="text-xl font-semibold text-gray-900">Detalles del Pedido #{pedido.id}</h2>
                                <button onClick={() => setDetalleView(false)} className="text-2xl font-bold text-gray-400 hover:text-gray-600">
                                    ×
                                </button>
                            </div>

                            {/* Content */}
                            <div className="p-6">
                                <div className="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                                    {/* Información del Pedido */}
                                    <div className="rounded-lg bg-gray-50 p-4">
                                        <h3 className="mb-3 font-semibold text-gray-900">Información del Pedido</h3>
                                        <div className="space-y-2">
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Tipo de Entrega:</span>
                                                <span className="font-medium">{pedido.tipo_entrega}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Tipo de Pago:</span>
                                                <span className="font-medium">{pedido.forma_pago}</span>
                                            </div>
                                            {pedido.mensaje && (
                                                <div className="pt-2">
                                                    <span className="text-gray-600">Mensaje:</span>
                                                    <p className="mt-1 rounded border bg-white p-2 text-sm text-gray-800">{pedido.mensaje}</p>
                                                </div>
                                            )}
                                        </div>
                                    </div>

                                    {/* Resumen de Costos */}
                                    <div className="rounded-lg bg-gray-50 p-4">
                                        <h3 className="mb-3 font-semibold text-gray-900">Resumen de Costos</h3>
                                        <div className="space-y-2">
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Subtotal:</span>
                                                <span className="font-medium">${pedido?.subtotal}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Descuento:</span>
                                                <span className="font-medium text-green-600">-${pedido.descuento}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">IVA:</span>
                                                <span className="font-medium">${pedido.iva}</span>
                                            </div>
                                            <div className="border-t pt-2">
                                                <div className="flex justify-between">
                                                    <span className="text-lg font-semibold text-gray-900">Total:</span>
                                                    <span className="text-lg font-semibold text-gray-900">${pedido.total}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {/* Productos */}
                                <div>
                                    <h3 className="mb-3 font-semibold text-gray-900">Productos</h3>
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full rounded-lg border border-gray-200 bg-white">
                                            <thead className="bg-gray-50">
                                                <tr>
                                                    <th className="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">
                                                        Código
                                                    </th>
                                                    <th className="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase">
                                                        Nombre
                                                    </th>
                                                    <th className="px-4 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase">
                                                        Cantidad
                                                    </th>
                                                    <th className="px-4 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase">
                                                        Precio
                                                    </th>
                                                    <th className="px-4 py-3 text-right text-xs font-medium tracking-wider text-gray-500 uppercase">
                                                        Subtotal
                                                    </th>
                                                </tr>
                                            </thead>
                                            {/*  <tbody className="divide-y divide-gray-200">
                                    {order.productos.map((producto, index) => (
                                        <tr key={index} className="hover:bg-gray-50">
                                            <td className="px-4 py-3 text-sm text-gray-900">
                                                {producto.codigo}
                                            </td>
                                            <td className="px-4 py-3 text-sm text-gray-900">
                                                {producto.nombre}
                                            </td>
                                            <td className="px-4 py-3 text-sm text-gray-900 text-center">
                                                {producto.cantidad}
                                            </td>
                                            <td className="px-4 py-3 text-sm text-gray-900 text-right">
                                                ${producto.precio}
                                            </td>
                                            <td className="px-4 py-3 text-sm text-gray-900 text-right font-medium">
                                                ${(producto.cantidad * producto.precio)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody> */}
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {/* Footer */}
                            <div className="flex justify-end border-t border-gray-200 p-6">
                                <button
                                    onClick={() => setDetalleView(false)}
                                    className="bg-primary-orange hover:bg-primary-orange/80 rounded-md px-4 py-2 text-white transition-colors"
                                >
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            <div className="grid grid-cols-7 gap-2 border-b py-2 text-[#74716A]">
                <div className="flex items-center">
                    <div className="flex h-[80px] w-[80px] items-center justify-center bg-[#F5F5F5]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43" fill="none">
                            <path
                                d="M10.7497 39.4167V7.16668C10.7497 6.21632 11.1272 5.30488 11.7992 4.63288C12.4712 3.96087 13.3826 3.58334 14.333 3.58334H28.6663C29.6167 3.58334 30.5281 3.96087 31.2001 4.63288C31.8721 5.30488 32.2497 6.21632 32.2497 7.16668V39.4167M10.7497 39.4167H32.2497M10.7497 39.4167H7.16634C6.21598 39.4167 5.30455 39.0391 4.63254 38.3671C3.96054 37.6951 3.58301 36.7837 3.58301 35.8333V25.0833C3.58301 24.133 3.96054 23.2215 4.63254 22.5495C5.30455 21.8775 6.21598 21.5 7.16634 21.5H10.7497M32.2497 39.4167H35.833C36.7834 39.4167 37.6948 39.0391 38.3668 38.3671C39.0388 37.6951 39.4163 36.7837 39.4163 35.8333V19.7083C39.4163 18.758 39.0388 17.8465 38.3668 17.1745C37.6948 16.5025 36.7834 16.125 35.833 16.125H32.2497M17.9163 10.75H25.083M17.9163 17.9167H25.083M17.9163 25.0833H25.083M17.9163 32.25H25.083"
                                stroke="#0072C6"
                                stroke-width="3"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                </div>
                <div className="flex items-center">{pedido?.id}</div>
                <div className="flex items-center">{pedido?.created_at}</div>
                <div className="flex items-center font-bold">{pedido?.entregado == 1 ? 'Entregado' : 'Pendiente'}</div>
                <div className="flex items-center">
                    $ {Number(pedido?.total).toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                </div>
                <div className="flex items-center">
                    <button onClick={() => setDetalleView(true)} className="bg-primary-orange h-10 w-full min-w-[138px] font-bold text-white">
                        Ver detalles
                    </button>
                </div>
                <div className="flex items-center">
                    <button onClick={recomprar} className="border-primary-orange text-primary-orange h-10 w-full min-w-[138px] border font-medium">
                        Recomprar
                    </button>
                </div>
            </div>
        </>
    );
}
