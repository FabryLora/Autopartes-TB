import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';
import UserSwitch from './switch';

export default function ClientesAdminRow({ cliente }) {
    const [edit, setEdit] = useState(false);

    const { provincias, listas, vendedores } = usePage().props;

    const updateForm = useForm({
        name: cliente?.name,
        email: cliente?.email,
        cuit: cliente?.cuit,
        direccion: cliente?.direccion,
        telefono: cliente?.telefono,
        lista_de_precios_id: cliente?.lista_de_precios_id,
        descuento_uno: cliente?.descuento_uno,
        descuento_dos: cliente?.descuento_dos,
        descuento_tres: cliente?.descuento_tres,
        provincia: cliente?.provincia,
        localidad: cliente?.localidad,
        autorizado: cliente?.autorizado,
        vendedor_id: cliente?.vendedor_id,
        id: cliente?.id,
    });

    const update = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        updateForm.post(route('admin.clientes.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Cliente actualizado correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar cliente');
                console.log(errors);
            },
        });
    };

    const deleteCliente = () => {
        if (confirm('¿Estas seguro de eliminar este cliente?')) {
            updateForm.delete(route('admin.clientes.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Cliente eliminada correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar cliente');
                    console.log(errors);
                },
            });
        }
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="pl-5 text-left">{cliente?.name}</td>
            <td className="text-left">{cliente?.email}</td>
            <td className="text-left">{cliente?.provincia}</td>
            <td className="text-left">{cliente?.localidad}</td>
            <td className="text-left">{cliente?.vendedor?.name}</td>
            <td className="h-[90px] text-center">{cliente?.lista_de_precios_id}</td>
            <td className="flex h-[90px] items-center justify-center">
                <UserSwitch routeName="admin.clientes.autorizar" id={cliente?.id} status={cliente?.autorizado == 1} />
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
                            <h2 className="text-xl font-bold text-black">Registrar cliente</h2>
                            <div className="grid w-full grid-cols-2 gap-3 text-[16px]">
                                <div className="col-span-2 flex flex-col gap-2">
                                    <label htmlFor="name" className="">
                                        Nombre de usuario
                                    </label>
                                    <input
                                        defaultValue={cliente?.name}
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
                                        defaultValue={cliente?.email}
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
                                        defaultValue={cliente?.cuit}
                                        onChange={(ev) => updateForm.setData('cuit', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="dni"
                                        id="dni"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="direccion">Dirección</label>
                                    <input
                                        defaultValue={cliente?.direccion}
                                        onChange={(ev) => updateForm.setData('direccion', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="direccion"
                                        id="direccion"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="telefono">Telefono</label>
                                    <input
                                        defaultValue={cliente?.telefono}
                                        onChange={(ev) => updateForm.setData('telefono', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="telefono"
                                        id="telefono"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="lista">Lista</label>
                                    <select
                                        required
                                        value={updateForm?.data?.lista_de_precios_id}
                                        onChange={(ev) => updateForm.setData('lista_de_precios_id', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        name="lista_de_precios_id"
                                        id="lista_de_precios_id"
                                    >
                                        <option disabled selected value="">
                                            Selecciona una lista
                                        </option>

                                        {listas?.map((lista) => (
                                            <option key={lista.id} value={lista.id}>
                                                {lista.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="vendedor">Vendedor</label>
                                    <select
                                        onChange={(ev) => updateForm.setData('vendedor_id', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        name="vendedor_id"
                                        id="vendedor_id"
                                    >
                                        <option disabled selected value="">
                                            Selecciona un vendedor
                                        </option>

                                        {vendedores?.map((vendedor) => (
                                            <option key={vendedor.id} value={vendedor.id}>
                                                {vendedor.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="col-span-2 grid grid-cols-3 gap-4">
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="descuento_uno">Descuento 1</label>
                                        <input
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            defaultValue={cliente?.descuento_uno}
                                            onChange={(e) => updateForm.setData('descuento_uno', e.target.value)}
                                            type="number"
                                            name=""
                                            id="descuento_uno"
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="descuento_dos">Descuento 2</label>
                                        <input
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            defaultValue={cliente?.descuento_dos}
                                            onChange={(e) => updateForm.setData('descuento_dos', e.target.value)}
                                            type="number"
                                            name=""
                                            id="descuento_dos"
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="descuento_tres">Descuento 3</label>
                                        <input
                                            className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                            defaultValue={cliente?.descuento_tres}
                                            onChange={(e) => updateForm.setData('descuento_tres', e.target.value)}
                                            type="number"
                                            name=""
                                            id="descuento_tres"
                                        />
                                    </div>
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="provincia">Provincia</label>
                                    <select
                                        defaultValue={cliente?.provincia}
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
                                        defaultValue={cliente?.localidad}
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
                                <button className="bg-primary-orange col-span-2 h-[43px] w-full text-white">Actualizar cliente</button>
                            </div>
                        </form>
                    </motion.div>
                )}
            </AnimatePresence>
        </tr>
    );
}
