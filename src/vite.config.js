import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/login.css",
                "resources/css/register.css",
                "resources/css/feed.css",
                "resources/css/offers.css",
                "resources/css/myRequests.css",
                "resources/css/assignment.css",
                "resources/css/tutorial.css",
                "resources/js/app.jsx",
                "resources/js/registerApp.jsx",
            ],
            refresh: true,
        }),
        tailwindcss(),
        react(),
    ],
    server: {
        host: "0.0.0.0",
        port: 5173,
        hmr: {
            host: "localhost",
        },
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
