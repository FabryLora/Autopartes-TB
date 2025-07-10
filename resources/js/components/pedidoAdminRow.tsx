import { router } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';
import toast from 'react-hot-toast';

export default function PedidoAdminRow({ pedido }) {
    const [pedidoView, setPedidoView] = useState(false);
    const modalRef = useRef(null);

    useEffect(() => {
        // Handle clicks outside the modal
        function handleClickOutside(event) {
            // Check if modalRef exists and if the click was outside the modal
            if (modalRef.current && !modalRef.current.contains(event.target)) {
                setPedidoView(false);
            }
        }

        // Add event listener when modal is open
        if (pedidoView) {
            document.addEventListener('mousedown', handleClickOutside);
        }

        // Clean up function
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [pedidoView]); // Re-run effect when pedidoView changes

    return (
        <>
            {pedidoView && (
                <div
                    className="fixed top-0 left-0 z-50 flex h-screen w-screen items-center justify-center bg-black/50"
                    onClick={() => setPedidoView(false)}
                >
                    <div className="fixed inset-0 z-50 flex items-center justify-center">
                        {/* Overlay */}

                        {/* Modal */}
                        <div className="relative mx-4 max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-lg bg-white shadow-xl">
                            {/* Header */}
                            <div className="flex items-center justify-between border-b border-gray-200 p-6">
                                <h2 className="text-xl font-semibold text-gray-900">Detalles del Pedido #{pedido.id}</h2>
                                <button onClick={() => setPedidoView(false)} className="text-2xl font-bold text-gray-400 hover:text-gray-600">
                                    ×
                                </button>
                            </div>

                            {/* Content */}
                            <div className="p-6">
                                {/* Datos del Usuario */}
                                <div className="mb-6 rounded-lg bg-blue-50 p-4">
                                    <h3 className="mb-3 font-semibold text-gray-900">Datos del Usuario</h3>
                                    <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                        <div className="space-y-2">
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Nombre:</span>
                                                <span className="font-medium">{pedido?.user?.nombre}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Email:</span>
                                                <span className="font-medium">{pedido?.user?.email}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">CUIT:</span>
                                                <span className="font-medium">{pedido?.user?.cuit}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Teléfono:</span>
                                                <span className="font-medium">{pedido?.user?.telefono}</span>
                                            </div>
                                        </div>
                                        <div className="space-y-2">
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Dirección:</span>
                                                <span className="font-medium">{pedido?.user?.direccion}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Provincia:</span>
                                                <span className="font-medium">{pedido?.user?.provincia}</span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Localidad:</span>
                                                <span className="font-medium">{pedido?.user?.localidad}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                                <span className="font-medium">
                                                    $
                                                    {Number(pedido?.subtotal).toLocaleString('es-AR', {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2,
                                                    })}
                                                </span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">Descuento:</span>
                                                <span className="font-medium text-green-600">
                                                    -$
                                                    {Number(pedido?.descuento).toLocaleString('es-AR', {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2,
                                                    })}
                                                </span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-gray-600">IVA:</span>
                                                <span className="font-medium">
                                                    $
                                                    {Number(pedido?.iva).toLocaleString('es-AR', {
                                                        minimumFractionDigits: 2,
                                                        maximumFractionDigits: 2,
                                                    })}
                                                </span>
                                            </div>
                                            <div className="border-t pt-2">
                                                <div className="flex justify-between">
                                                    <span className="text-lg font-semibold text-gray-900">Total:</span>
                                                    <span className="text-lg font-semibold text-gray-900">
                                                        $
                                                        {Number(pedido?.total).toLocaleString('es-AR', {
                                                            minimumFractionDigits: 2,
                                                            maximumFractionDigits: 2,
                                                        })}
                                                    </span>
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
                                            <tbody className="divide-y divide-gray-200">
                                                {pedido.productos.map((producto, index) => (
                                                    <tr key={index} className="hover:bg-gray-50">
                                                        <td className="px-4 py-3 text-left text-sm text-gray-900">{producto?.producto?.code}</td>
                                                        <td className="px-4 py-3 text-left text-sm text-gray-900">{producto?.producto?.name}</td>
                                                        <td className="px-4 py-3 text-center text-sm text-gray-900">
                                                            {producto.cantidad.toLocaleString('es-AR')}
                                                        </td>
                                                        <td className="px-4 py-3 text-right text-sm text-gray-900">
                                                            $
                                                            {Number(producto.precio_unitario).toLocaleString('es-AR', {
                                                                minimumFractionDigits: 2,
                                                                maximumFractionDigits: 2,
                                                            })}
                                                        </td>
                                                        <td className="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                                            $
                                                            {Number(producto.cantidad * producto.precio_unitario).toLocaleString('es-AR', {
                                                                minimumFractionDigits: 2,
                                                                maximumFractionDigits: 2,
                                                            })}
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {/* Footer */}
                            <div className="flex justify-end border-t border-gray-200 p-6">
                                <button
                                    onClick={() => setPedidoView(false)}
                                    className="bg-primary-orange hover:bg-primary-orange/80 rounded-md px-4 py-2 text-white transition-colors"
                                >
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
            <tr className="odd:bg-gray-200">
                <td className="h-[90px]">{pedido?.id}</td>
                <td>{pedido?.user?.name}</td>
                <td>
                    <select
                        defaultValue={pedido.estado}
                        onChange={(e) => {
                            router.post(
                                route('cambiarEstado'),
                                {
                                    id: pedido.id,
                                    estado: e.target.value,
                                },
                                {
                                    onSuccess: () => {
                                        toast.success('Estado del pedido actualizado correctamente');
                                    },
                                },
                            );
                        }}
                        name="estado"
                        id=""
                    >
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="Completado">Completado</option>
                    </select>
                </td>
                <td>
                    <button
                        onClick={() => setPedidoView(true)}
                        className="bg-primary-orange hover:text-primary-orange hover:outline-primary-orange rounded-md px-4 py-2 font-bold text-white transition duration-300 hover:bg-transparent hover:outline"
                    >
                        VER PEDIDO
                    </button>
                </td>
            </tr>
        </>
    );
}
