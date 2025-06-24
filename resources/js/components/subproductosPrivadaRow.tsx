import { faChevronDown, faChevronUp } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import { useCart } from 'react-use-cart';
/* import defaultPhoto from '../../images/defaultPhoto.png'; */

export default function SubproductosPrivadaRow({ subProducto }) {
    const { addItem, updateItemQuantity, getItem, removeItem } = useCart();
    const { auth, ziggy } = usePage().props;
    const { user } = auth;

    const currentItem = getItem(subProducto?.id) ? getItem(subProducto?.id) : null;

    const [cantidad, setCantidad] = useState(currentItem?.quantity || 1);

    const handlePrice = () => {
        let price = 0;
        if (user.lista == '1') {
            price = subProducto?.price_mayorista;
        }
        if (user.lista == '2') {
            price = subProducto?.price_minorista;
        }
        if (user.lista == '3') {
            price = subProducto?.price_dist;
        }

        return Number(price);
    };

    useEffect(() => {
        if (currentItem) {
            updateItemQuantity(subProducto?.id, cantidad);
        }
    }, [cantidad]);

    return (
        <div className="grid h-fit grid-cols-8 items-center border-b border-gray-200 py-2 text-[15px] text-[#74716A]">
            <div className="h-[85px] w-[85px]">
                <img src={subProducto?.image} className="h-full w-full object-contain" alt="" />
            </div>
            <p className="">{subProducto?.code}</p>
            <p className="">{subProducto?.producto?.marca?.name}</p>
            <p className="">{subProducto?.producto?.name}</p>
            <p className="">{subProducto?.description}</p>
            <p className="pl-4">$ {handlePrice()?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}</p>
            <p className="flex justify-center">
                <div className="flex h-[38px] w-[99px] flex-row items-center border border-[#EEEEEE] px-2">
                    <input value={cantidad} type="text" className="h-full w-full focus:outline-none" />
                    <div className="flex h-full flex-col justify-center">
                        <button onClick={() => setCantidad(cantidad + 1)} className="flex items-center">
                            <FontAwesomeIcon icon={faChevronUp} size="xs" />
                        </button>
                        <button onClick={() => setCantidad(cantidad > 1 ? cantidad - 1 : cantidad)} className="flex items-center">
                            <FontAwesomeIcon icon={faChevronDown} size="xs" />
                        </button>
                    </div>
                </div>
            </p>
            <p className="flex justify-center">
                {ziggy.location.includes('carrito') ? (
                    <button
                        onClick={() => removeItem(subProducto?.id)}
                        className="border-primary-orange text-primary-orange hover:bg-primary-orange h-[41px] w-[88px] border text-[16px] font-bold transition duration-300 hover:text-white"
                    >
                        Eliminar
                    </button>
                ) : (
                    <button
                        onClick={() => {
                            addItem({ ...subProducto, price: handlePrice(), id: subProducto?.id }, cantidad);
                            toast.success('Producto agregado al carrito', { position: 'bottom-left' });
                        }}
                        className=""
                    >
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
    );
}
