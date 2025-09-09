import axios from 'axios';

export default function ListaDePreciosRow({ lista }) {
    const handleDownload = async () => {
        try {
            const filename = lista?.archivo.split('/').pop();
            // Make a GET request to the download endpoint
            const response = await axios.get(`/descargar/archivo/${filename}`, {
                responseType: 'blob', // Important for file downloads
            });

            // Create a link element to trigger the download
            const fileType = response.headers['content-type'] || 'application/octet-stream';
            const blob = new Blob([response.data], { type: fileType });
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = lista?.name; // Descargar con el nombre original
            document.body.appendChild(a);
            a.click();

            window.URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Download failed:', error);

            // Optional: show user-friendly error message
            alert('Failed to download the file. Please try again.');
        }
    };
    return (
        <>
            <div className="grid grid-cols-5 gap-2 border-b py-2 text-[#74716A] max-sm:w-full max-sm:grid-cols-1 max-sm:gap-4 max-sm:py-4">
                <div className="flex items-center max-sm:justify-center">
                    <div className="flex h-[80px] w-[80px] items-center justify-center bg-[#F5F5F5] max-sm:h-[60px] max-sm:w-[60px]">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="44"
                            height="44"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="#0072c6"
                            strokeWidth="2"
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            className="lucide lucide-file-text-icon lucide-file-text max-sm:h-[32px] max-sm:w-[32px]"
                        >
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                    </div>
                </div>
                <div className="flex items-center max-sm:justify-center max-sm:text-lg max-sm:font-semibold">{lista?.name}</div>
                <div className="flex items-center uppercase max-sm:justify-center max-sm:text-sm">
                    <span className="hidden max-sm:mr-2 max-sm:inline max-sm:font-medium">Formato:</span>
                    {lista?.formato}
                </div>
                <div className="flex items-center max-sm:justify-center max-sm:text-sm">
                    <span className="hidden max-sm:mr-2 max-sm:inline max-sm:font-medium">Peso:</span>
                    {lista?.peso_archivo}
                </div>
                <div className="flex items-center max-sm:mt-2 max-sm:justify-center">
                    <button onClick={handleDownload} className="block w-full max-sm:w-auto">
                        <button className="bg-primary-orange h-10 w-full min-w-[138px] font-bold text-white max-sm:px-6">Descargar</button>
                    </button>
                </div>
            </div>
        </>
    );
}
