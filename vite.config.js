import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import vuetify from 'vite-plugin-vuetify'
//import VueDevTools from 'vite-plugin-vue-devtools';

export default defineConfig({
    plugins: [
        /*
        VueDevTools({
            appendTo: 'resources/js/app.js'
        }),
        */
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({ autoImport: true }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            '@images': path.resolve(__dirname, './resources/images'),
        },
    },
});
