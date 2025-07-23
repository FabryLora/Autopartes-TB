import { faChevronDown, faChevronUp } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { router, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
/* import defaultPhoto from '../../images/defaultPhoto.png'; */

export default function ProductosPrivadaRow({ producto, margenSwitch, margen }) {
    const { auth, ziggy } = usePage().props;
    const { user } = auth;

    const [cantidad, setCantidad] = useState(producto?.qty != 1 ? producto?.qty : producto?.unidad_pack);

    useEffect(() => {
        if (producto?.rowId) {
            router.post(
                route('update'),
                {
                    qty: cantidad,
                    rowId: producto?.rowId,
                },
                {
                    preserveScroll: true,
                },
            );
        }
    }, [cantidad]);

    const { post, setData } = useForm({
        id: producto?.id,
        name: producto?.code,
        qty: cantidad,
        price: producto?.precio?.precio,
        rowId: producto?.rowId,
    });

    useEffect(() => {
        setData({
            id: producto?.id,
            name: producto?.code,
            qty: cantidad,
            price: producto?.precio?.precio,
            rowId: producto?.rowId,
        });
    }, [cantidad, producto]);

    const addToCart = (e) => {
        e.preventDefault();

        post(route('addtocart'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Producto agregado al carrito', { position: 'top-center' });
            },
            onError: (errors) => {
                toast.error('Error al agregar producto al carrito');
                console.log(errors);
            },
        });
    };

    const removeFromCart = (e) => {
        e.preventDefault();
        post(route('remove'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Producto eliminado del carrito', { position: 'top-center' });
            },
            onError: (errors) => {
                toast.error('Error al eliminar producto del carrito');
                console.log(errors);
            },
        });
    };

    return (
        <>
            {/* Vista desktop - tabla */}
            <div className="grid h-fit grid-cols-9 items-center border-b border-gray-200 py-2 text-[15px] text-black max-sm:hidden">
                <div className="h-[85px] w-[85px]">
                    <img src={producto?.imagenes[0]?.image} className="h-full w-full object-contain" alt="" />
                </div>
                <p className="">{producto?.code}</p>
                {/* mostrar uno debajo del otro */}
                <p className="">
                    {producto?.code_oem?.split('/').map((item) => (
                        <span key={item} className="block">
                            {item}
                        </span>
                    ))}
                </p>
                <p className="">{producto?.name}</p>

                {margenSwitch ? (
                    <div className="relative">
                        {producto?.oferta == 1 && <p className="absolute -top-5 w-full text-right text-xs font-bold text-green-500">OFERTA</p>}
                        <p className="text-right">
                            ${' '}
                            {(
                                Number(
                                    producto?.oferta == 1
                                        ? Number(producto?.precio?.precio) * (1 - Number(producto?.descuento_oferta) / 100)
                                        : Number(producto?.precio?.precio),
                                ) *
                                (1 + Number(margen) / 100)
                            )?.toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                        </p>
                        <p className="absolute w-full text-right text-gray-400 line-through">
                            ${' '}
                            {Number(
                                producto?.oferta == 1
                                    ? Number(producto?.precio?.precio) * (1 + Number(producto?.descuento_oferta) / 100)
                                    : Number(producto?.precio?.precio),
                            )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                        </p>
                    </div>
                ) : (
                    <div className="relative">
                        {producto?.oferta == 1 && <p className="absolute -top-5 w-full text-right text-xs font-bold text-green-500">OFERTA</p>}
                        <p className="text-right">
                            ${' '}
                            {Number(
                                producto?.oferta == 1
                                    ? Number(producto?.precio?.precio) * (1 + Number(producto?.descuento_oferta) / 100)
                                    : producto?.precio?.precio,
                            )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                        </p>
                    </div>
                )}

                <p className="flex justify-end">
                    <div className="flex h-[38px] w-[99px] flex-row items-center border border-[#EEEEEE] px-2">
                        <input value={cantidad} type="text" className="h-full w-full focus:outline-none" />
                        <div className="flex h-full flex-col justify-center">
                            <button onClick={() => setCantidad(Number(cantidad) + Number(producto?.unidad_pack))} className="flex items-center">
                                <FontAwesomeIcon icon={faChevronUp} size="xs" />
                            </button>
                            <button
                                onClick={() =>
                                    setCantidad(
                                        Number(cantidad) > producto?.unidad_pack
                                            ? Number(cantidad) - Number(producto?.unidad_pack)
                                            : Number(cantidad),
                                    )
                                }
                                className="flex items-center"
                            >
                                <FontAwesomeIcon icon={faChevronDown} size="xs" />
                            </button>
                        </div>
                    </div>
                </p>

                {margenSwitch ? (
                    <div className="relative">
                        <p className="text-right">
                            ${' '}
                            {(
                                Number(
                                    producto?.oferta == 1
                                        ? Number(producto?.precio?.precio) * cantidad * (1 - Number(producto?.descuento_oferta) / 100)
                                        : Number(producto?.precio?.precio) * cantidad,
                                ) *
                                (1 + Number(margen) / 100)
                            )?.toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                        </p>
                        <p className="absolute w-full text-right text-gray-400 line-through">
                            ${' '}
                            {Number(
                                producto?.oferta == 1
                                    ? Number(producto?.precio?.precio) * cantidad * (1 - Number(producto?.descuento_oferta) / 100)
                                    : producto?.precio?.precio * cantidad,
                            )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                        </p>
                    </div>
                ) : (
                    <p className="text-right">
                        ${' '}
                        {Number(
                            producto?.oferta == 1
                                ? Number(producto?.precio?.precio) * cantidad * (1 - Number(producto?.descuento_oferta) / 100)
                                : producto?.precio?.precio * cantidad,
                        )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                    </p>
                )}
                <p className="flex justify-center">
                    <div className="h-[12px] w-[12px] rounded-full bg-gray-200"></div>
                </p>

                <p className="flex justify-center">
                    {ziggy.location.includes('carrito') ? (
                        <button type="button" onClick={removeFromCart} className="">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="#0072c6"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                className="lucide lucide-trash-icon lucide-trash"
                            >
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                            </svg>
                        </button>
                    ) : (
                        <button onClick={addToCart} className="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22" fill="none">
                                <path
                                    d="M0 0H4.764L5.309 2H23.387L19.721 13H7.78L7.28 15H22V17H4.72L5.966 12.011L3.236 2H0V0ZM4 20C4 19.4696 4.21071 18.9609 4.58579 18.5858C4.96086 18.2107 5.46957 18 6 18C6.53043 18 7.03914 18.2107 7.41421 18.5858C7.78929 18.9609 8 19.4696 8 20C8 20.5304 7.78929 21.0391 7.41421 21.4142C7.03914 21.7893 6.53043 22 6 22C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20ZM18 20C18 19.4696 18.2107 18.9609 18.5858 18.5858C18.9609 18.2107 19.4696 18 20 18C20.5304 18 21.0391 18.2107 21.4142 18.5858C21.7893 18.9609 22 19.4696 22 20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22C19.4696 22 18.9609 21.7893 18.5858 21.4142C18.2107 21.0391 18 20.5304 18 20Z"
                                    fill="#0072C6"
                                />
                            </svg>
                        </button>
                    )}
                </p>
            </div>

            {/* Vista móvil - tarjeta */}
            <div className="mb-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:hidden">
                {/* Header de la tarjeta */}
                <div className="mb-3 flex items-start gap-3">
                    <div className="h-[60px] w-[60px] flex-shrink-0">
                        <img src={producto?.imagenes[0]?.image} className="h-full w-full object-contain" alt="" />
                    </div>
                    <div className="min-w-0 flex-1">
                        <div className="mb-1 flex items-center justify-between">
                            <p className="text-sm font-medium text-gray-900">{producto?.code}</p>
                            {producto?.oferta == 1 && <span className="rounded bg-green-50 px-2 py-1 text-xs font-bold text-green-500">OFERTA</span>}
                        </div>
                        <p className="line-clamp-2 text-sm text-gray-600">{producto?.name}</p>
                        {producto?.code_oem && (
                            <div className="grid grid-cols-2">
                                <p className="text-xs text-gray-500">Codigo OEM:</p>
                                <div className="flex flex-col gap-1">
                                    {producto?.code_oem?.split('/').map((item) => (
                                        <span key={item} className="block text-xs text-gray-500">
                                            {item}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Precio */}
                <div className="mb-3">
                    {margenSwitch ? (
                        <div className="text-right">
                            <p className="text-lg font-semibold text-gray-900">
                                ${' '}
                                {(
                                    Number(
                                        producto?.oferta == 1
                                            ? Number(producto?.precio?.precio) * (1 - Number(producto?.descuento_oferta) / 100)
                                            : Number(producto?.precio?.precio),
                                    ) *
                                    (1 - Number(margen) / 100)
                                )?.toLocaleString('es-AR', {
                                    maximumFractionDigits: 2,
                                    minimumFractionDigits: 2,
                                })}
                            </p>
                            <p className="text-sm text-gray-400 line-through">
                                ${' '}
                                {Number(
                                    producto?.oferta == 1
                                        ? Number(producto?.precio?.precio) * (1 - Number(producto?.descuento_oferta) / 100)
                                        : Number(producto?.precio?.precio),
                                )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                            </p>
                        </div>
                    ) : (
                        <div className="text-right">
                            <p className="text-lg font-semibold text-gray-900">
                                ${' '}
                                {Number(
                                    producto?.oferta == 1
                                        ? Number(producto?.precio?.precio) * (1 - Number(producto?.descuento_oferta) / 100)
                                        : producto?.precio?.precio,
                                )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                            </p>
                        </div>
                    )}
                </div>

                {/* Controles de cantidad y acciones */}
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        {/* Control de cantidad */}
                        <div className="flex h-[32px] w-[80px] flex-row items-center rounded border border-[#EEEEEE] px-2">
                            <input value={cantidad} type="text" className="h-full w-full text-center text-sm focus:outline-none" readOnly />
                            <div className="flex h-full flex-col justify-center">
                                <button onClick={() => setCantidad(Number(cantidad) + Number(producto?.unidad_pack))} className="flex items-center">
                                    <FontAwesomeIcon icon={faChevronUp} size="xs" />
                                </button>
                                <button
                                    onClick={() =>
                                        setCantidad(
                                            Number(cantidad) > producto?.unidad_pack
                                                ? Number(cantidad) - Number(producto?.unidad_pack)
                                                : Number(cantidad),
                                        )
                                    }
                                    className="flex items-center"
                                >
                                    <FontAwesomeIcon icon={faChevronDown} size="xs" />
                                </button>
                            </div>
                        </div>

                        {/* Indicador de stock */}
                        <div className="flex items-center gap-1">
                            <div className="h-[8px] w-[8px] rounded-full bg-gray-200"></div>
                            <span className="text-xs text-gray-500">Stock</span>
                        </div>
                    </div>

                    {/* Botón de acción */}
                    <div className="flex items-center gap-2">
                        {/* Subtotal */}
                        {margenSwitch ? (
                            <div className="text-right">
                                <p className="text-sm font-medium text-gray-900">
                                    ${' '}
                                    {(
                                        Number(
                                            producto?.oferta == 1
                                                ? Number(producto?.precio?.precio) * cantidad * (1 - Number(producto?.descuento_oferta) / 100)
                                                : Number(producto?.precio?.precio) * cantidad,
                                        ) *
                                        (1 - Number(margen) / 100)
                                    )?.toLocaleString('es-AR', {
                                        maximumFractionDigits: 2,
                                        minimumFractionDigits: 2,
                                    })}
                                </p>
                                <p className="text-xs text-gray-400 line-through">
                                    ${' '}
                                    {Number(
                                        producto?.oferta == 1
                                            ? Number(producto?.precio?.precio) * cantidad * (1 - Number(producto?.descuento_oferta) / 100)
                                            : producto?.precio?.precio * cantidad,
                                    )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                                </p>
                            </div>
                        ) : (
                            <p className="text-sm font-medium text-gray-900">
                                ${' '}
                                {Number(
                                    producto?.oferta == 1
                                        ? Number(producto?.precio?.precio) * cantidad * (1 - Number(producto?.descuento_oferta) / 100)
                                        : producto?.precio?.precio * cantidad,
                                )?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                            </p>
                        )}

                        {/* Botón */}
                        {ziggy.location.includes('carrito') ? (
                            <button type="button" onClick={removeFromCart} className="rounded p-2 text-red-500 hover:bg-red-50">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                >
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                </svg>
                            </button>
                        ) : (
                            <button onClick={addToCart} className="rounded p-2 text-blue-600 hover:bg-blue-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 24 22" fill="none">
                                    <path
                                        d="M0 0H4.764L5.309 2H23.387L19.721 13H7.78L7.28 15H22V17H4.72L5.966 12.011L3.236 2H0V0ZM4 20C4 19.4696 4.21071 18.9609 4.58579 18.5858C4.96086 18.2107 5.46957 18 6 18C6.53043 18 7.03914 18.2107 7.41421 18.5858C7.78929 18.9609 8 19.4696 8 20C8 20.5304 7.78929 21.0391 7.41421 21.4142C7.03914 21.7893 6.53043 22 6 22C5.46957 22 4.96086 21.7893 4.58579 21.4142C4.21071 21.0391 4 20.5304 4 20ZM18 20C18 19.4696 18.2107 18.9609 18.5858 18.5858C18.9609 18.2107 19.4696 18 20 18C20.5304 18 21.0391 18.2107 21.4142 18.5858C21.7893 18.9609 22 19.4696 22 20C22 20.5304 21.7893 21.0391 21.4142 21.4142C21.0391 21.7893 20.5304 22 20 22C19.4696 22 18.9609 21.7893 18.5858 21.4142C18.2107 21.0391 18 20.5304 18 20Z"
                                        fill="currentColor"
                                    />
                                </svg>
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
