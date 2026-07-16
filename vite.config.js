import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    // TAMBAHKAN BLOK INI: Mengamankan asset URL agar digenerate sebagai HTTPS di server cloud
    server: {
        https: true,
        hmr: {
            protocol: "wss",
        },
    },
});
