import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';
import UserSwitch from './switch';

export default function VendedoresAdminRow({ vendedor }) {
    const [edit, setEdit] = useState(false);

    const { provincias } = usePage().props;

    const updateForm = useForm({
        name: vendedor?.name,

        email: vendedor?.email,
        cuit: vendedor?.cuit,
        direccion: vendedor?.direccion,
        telefono: vendedor?.telefono,
        descuento_uno: vendedor?.descuento_uno,
        descuento_dos: vendedor?.descuento_dos,
        descuento_tres: vendedor?.descuento_tres,
        rol: 'vendedor',
        provincia: vendedor?.provincia,
        localidad: vendedor?.localidad,
        autorizado: vendedor?.autorizado,
        id: vendedor?.id,
    });

    const update = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        updateForm.post(route('admin.clientes.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Vendedor actualizado correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar vendedor');
                console.log(errors);
            },
        });
    };

    const deleteCliente = () => {
        if (confirm('¿Estas seguro de eliminar este cliente?')) {
            updateForm.delete(route('admin.clientes.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Vendedor eliminado correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar vendedor');
                    console.log(errors);
                },
            });
        }
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="pl-5 text-left">{vendedor?.name}</td>
            <td className="text-left">{vendedor?.email}</td>

            <td className="h-[90px]">
                <UserSwitch routeName="admin.clientes.autorizar" id={vendedor?.id} status={vendedor?.autorizado == 1} />
            </td>

            <td className="w-[140px] text-center">
                <div className="flex flex-row justify-center gap-3">
                    <button onClick={() => setEdit(true)} className="h-10 w-10 rounded-md border border-blue-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faPen} size="lg" color="#3b82f6" />
                    </button>
                    <button onClick={deleteCliente} className="h-10 w-10 rounded-md border border-red-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faTrash} size="lg" color="#fb2c36" />
                    </button>
                </div>
            </td>
            <AnimatePresence>
                {edit && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                    >
                        <form onSubmit={update} className="flex h-fit w-[600px] flex-col gap-6 bg-white p-5 shadow-md">
                            <h2 className="text-xl font-bold text-black">Registrar Vendedor</h2>
                            <div className="grid w-full grid-cols-2 gap-3 text-[16px]">
                                <div className="col-span-2 flex flex-col gap-2">
                                    <label htmlFor="name" className="">
                                        Nombre de usuario
                                    </label>
                                    <input
                                        defaultValue={vendedor?.name}
                                        onChange={(ev) => updateForm.setData('name', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="name"
                                        id="name"
                                        required
                                    />
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label htmlFor="password">Contraseña</label>
                                    <input
                                        defaultValue={vendedor?.password}
                                        onChange={(ev) => updateForm.setData('password', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="password"
                                        name="password"
                                        id="password"
                                    />
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label htmlFor="password_confirmation">Confirmar contraseña</label>
                                    <input
                                        defaultValue={vendedor?.password_confirmation}
                                        onChange={(ev) => updateForm.setData('password_confirmation', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                    />
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label htmlFor="email">Email</label>
                                    <input
                                        defaultValue={vendedor?.email}
                                        onChange={(ev) => updateForm.setData('email', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="email"
                                        name="email"
                                        id="email"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="dni">Cuit</label>
                                    <input
                                        defaultValue={vendedor?.cuit}
                                        onChange={(ev) => updateForm.setData('cuit', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="dni"
                                        id="dni"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="telefono">Telefono</label>
                                    <input
                                        defaultValue={vendedor?.telefono}
                                        onChange={(ev) => updateForm.setData('telefono', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="telefono"
                                        id="telefono"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="direccion">Dirección</label>
                                    <input
                                        defaultValue={vendedor?.direccion}
                                        onChange={(ev) => updateForm.setData('direccion', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="direccion"
                                        id="direccion"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="provincia">Provincia</label>
                                    <select
                                        defaultValue={vendedor?.provincia}
                                        required
                                        onChange={(ev) => updateForm.setData('provincia', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        name="provincia"
                                        id="provincia"
                                    >
                                        <option disabled selected value="">
                                            Selecciona una provincia
                                        </option>

                                        {provincias?.map((pr) => (
                                            <option key={pr.id} value={pr.name}>
                                                {pr.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label htmlFor="localidad">Localidad</label>
                                    <select
                                        defaultValue={vendedor?.localidad}
                                        required
                                        onChange={(ev) => updateForm.setData('localidad', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        name="localidad"
                                        id="localidad"
                                    >
                                        <option disabled selected value="">
                                            Selecciona una localidad
                                        </option>

                                        {provincias
                                            ?.find((pr) => pr.name === updateForm?.data?.provincia)
                                            ?.localidades.map((loc, index) => (
                                                <option key={index} value={loc.name}>
                                                    {loc.name}
                                                </option>
                                            ))}
                                    </select>
                                </div>
                            </div>
                            <div className="flex flex-row justify-between gap-4">
                                <button
                                    type="button"
                                    onClick={() => setEdit(false)}
                                    className="bg-primary-orange col-span-2 h-[43px] w-full text-white"
                                >
                                    Cancelar
                                </button>
                                <button className="bg-primary-orange col-span-2 h-[43px] w-full text-white">Actualizar vendedor</button>
                            </div>
                        </form>
                    </motion.div>
                )}
            </AnimatePresence>
        </tr>
    );
}
