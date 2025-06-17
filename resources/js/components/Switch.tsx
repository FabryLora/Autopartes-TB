import { router } from '@inertiajs/react';
import { useState } from 'react';

export default function Switch({ status = false, routeName = '', id }) {
    const [checked, setChecked] = useState(status);

    const handleChange = () => {
        // Actualizamos el estado local para una respuesta inmediata en la UI
        const newStatus = !checked;
        setChecked(newStatus);
        // Enviamos la petición al servidor usando useForm
        router.post(route(routeName), { id: id });
    };

    return (
        <div className="flex items-center">
            <button
                onClick={handleChange}
                className={`relative flex h-6 w-12 cursor-pointer items-center rounded-full p-1 transition-colors duration-300 ${
                    checked ? 'bg-blue-500' : 'bg-gray-300'
                }`}
                aria-checked={checked}
                role="switch"
            >
                <span
                    className={`absolute h-5 w-5 transform rounded-full bg-white shadow-md transition-transform duration-300 ${
                        checked ? 'translate-x-5' : 'translate-x-0'
                    }`}
                />
            </button>
        </div>
    );
}
