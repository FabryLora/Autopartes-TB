export default function ListaDePreciosRow({ lista }) {
    return (
        <>
            <div className="grid grid-cols-5 gap-2 border-b py-2 text-[#74716A]">
                <div className="flex items-center">
                    <div className="flex h-[80px] w-[80px] items-center justify-center bg-[#F5F5F5]">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="44"
                            height="44"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#0072c6"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            className="lucide lucide-file-text-icon lucide-file-text"
                        >
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                    </div>
                </div>
                <div className="flex items-center">{lista?.name}</div>
                <div className="flex items-center uppercase">{lista?.formato}</div>
                <div className="flex items-center">{lista?.peso_archivo}</div>
                <div className="flex items-center">
                    <a href={lista?.archivo} target="_blank" rel="noopener noreferrer" className="block w-full">
                        <button className="bg-primary-orange h-10 w-full min-w-[138px] font-bold text-white">Ver Online</button>
                    </a>
                </div>
            </div>
        </>
    );
}
