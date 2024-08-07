import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // https: true,
        hmr: {
            host: 'frontend.local.diegofelix.com.br',
            protocol: 'wss',
            // clientPort: 443,
        },
    },


    /*
    // dessa forma aqui da badgaway no browser e no laravel
    // o laravel também reclama de CORS
    server: {
        https: true,
        hmr: {
            host: 'frontend.local.diegofelix.com.br',
            protocol: 'wss',
            clientPort: 443,
        },
    },
     */

    /*
    // dessa forma aqui da badgateway no browser
    // se rodar frontend.local.diegofelix.com.br:5173 ou acessando laravel
    // da erro de ssl ERR_SSL_VERSION_OR_CIPHER_MISMATCH
    server: {
        https: true,
        hmr: {
            host: 'frontend.local.diegofelix.com.br',
            protocol: 'wss',
            // clientPort: 443,
        },
    },
    */

    /*
    // dessa forma aqui meio que funciona, porque o script eh carregado via https
    // porque o traefik faz esse trabalho de transformar em ssl
    // porem o @vite que tem no laravel usa http em vez de https.
    // ou seja, funciona no navegador bonitinho, mas o front do laravel tenta
    // carregar http em vez de https
    server: {
        // https: true,
        hmr: {
            host: 'frontend.local.diegofelix.com.br',
            // protocol: 'wss',
            // clientPort: 443,
        },
    },
    */
});
