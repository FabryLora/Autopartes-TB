import SliderRow from '@/components/sliderRow';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';

export default function MarcasAdmin() {
    const { slider } = usePage().props;

    const { data, setData, post, reset, errors } = useForm({
        title: '',
    });

    const [searchTerm, setSearchTerm] = useState('');
    const [createView, setCreateView] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route('admin.slider.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Campo creado correctamente');
                reset();
                setCreateView(false);
            },
            onError: (errors) => {
                toast.error(`Error al crear campo: \n${Object.values(errors).join('\n')}`);
                console.log(errors);
            },
        });
    };

    return (
        <Dashboard>
            <div className="flex w-full flex-col p-6">
                <AnimatePresence>
                    {createView && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                        >
                            <form onSubmit={handleSubmit} method="POST" className="text-black">
                                <div className="w-[500px] rounded-md bg-white p-4">
                                    <h2 className="mb-4 text-2xl font-semibold">Crear Campo</h2>
                                    <div className="flex flex-col gap-4">
                                        <label htmlFor="ordennn">Orden</label>
                                        <input
                                            className={`focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline ${
                                                errors.order ? 'outline-red-500' : ''
                                            }`}
                                            type="text"
                                            name="ordennn"
                                            id="ordennn"
                                            onChange={(e) => setData('order', e.target.value)}
                                        />
                                        {errors.order && <p className="mt-1 text-xs text-red-500">{errors.order}</p>}

                                        <label htmlFor="nombree">
                                            Titulo <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            className={`focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline ${
                                                errors.title ? 'outline-red-500' : ''
                                            }`}
                                            type="text"
                                            name="nombree"
                                            id="nombree"
                                            onChange={(e) => setData('title', e.target.value)}
                                        />
                                        {errors.title && <p className="mt-1 text-xs text-red-500">{errors.title}</p>}

                                        <label htmlFor="link">Link</label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="link"
                                            id="link"
                                            value={data?.link}
                                            onChange={(e) => setData('link', e.target.value)}
                                        />
                                        {errors?.link && <p className="mt-1 text-xs text-red-500">{errors?.link}</p>}
                                        <label htmlFor="imagenn">
                                            Multimedia<span className="text-red-500">*</span>
                                        </label>
                                        <span className="text-base font-normal">Resolucion recomendada: 1366x598px</span>
                                        <div className="flex flex-col">
                                            <div className="flex flex-row">
                                                <input
                                                    type="file"
                                                    name="imagen"
                                                    id="imagenn"
                                                    onChange={(e) => setData('media', e.target.files[0])}
                                                    className="hidden"
                                                />
                                                <label
                                                    className={`border-primary-orange text-primary-orange hover:bg-primary-orange cursor-pointer rounded-md border px-2 py-1 transition duration-300 hover:text-white ${
                                                        errors.media ? 'border-red-500 text-red-500' : ''
                                                    }`}
                                                    htmlFor="imagenn"
                                                >
                                                    Elegir Multimedia
                                                </label>
                                                <p className="self-center px-2">{data?.media?.name}</p>
                                            </div>
                                            {errors.media && <p className="mt-1 text-xs text-red-500">{errors.media}</p>}
                                        </div>

                                        <div className="flex justify-end gap-4">
                                            <button
                                                type="button"
                                                onClick={() => setCreateView(false)}
                                                className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                            >
                                                Cancelar
                                            </button>
                                            <button
                                                type="submit"
                                                className="bg-primary-orange hover:text-primary-orange hover:border-primary-orange rounded-md border px-2 py-1 text-white transition duration-300 hover:border hover:bg-transparent"
                                            >
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </motion.div>
                    )}
                </AnimatePresence>
                <div className="mx-auto flex w-full flex-col gap-3">
                    <div className="flex h-fit w-full flex-row gap-5">
                        <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Slider</h2>
                        <button
                            onClick={() => setCreateView(true)}
                            className="bg-primary-orange w-[200px] rounded px-4 py-1 font-bold text-white hover:bg-red-500"
                        >
                            Crear Slider
                        </button>
                    </div>

                    <div className="flex w-full justify-center">
                        <table className="w-full border text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead className="bg-gray-300 text-sm font-medium text-black uppercase">
                                <tr>
                                    <td className="text-center">ORDEN</td>
                                    <td className="text-center">TITULO</td>
                                    <td className="text-center">LINK</td>
                                    <td className="w-[400px] px-3 py-2 text-center">MULTIMEDIA</td>
                                    <td className="text-center">EDITAR</td>
                                </tr>
                            </thead>
                            <tbody className="text-center">{slider?.map((slider) => <SliderRow key={slider.id} sliderObject={slider} />)}</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </Dashboard>
    );
}
