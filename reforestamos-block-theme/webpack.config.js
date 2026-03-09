/**
 * WordPress Scripts Webpack Configuration
 * 
 * This extends the default @wordpress/scripts webpack config
 * with production optimizations
 * 
 * @package Reforestamos
 */

const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
    ...defaultConfig,
    entry: {
        // Keep the default index entry
        index: path.resolve(process.cwd(), 'src', 'index.js'),
        // Add frontend entry
        frontend: path.resolve(process.cwd(), 'src/js', 'frontend.js'),
        // Add language switcher entry
        'language-switcher': path.resolve(process.cwd(), 'src/js', 'language-switcher.js'),
        // Add search filters entry
        'search-filters': path.resolve(process.cwd(), 'src/js', 'search-filters.js'),
    },
    optimization: {
        ...defaultConfig.optimization,
        minimize: process.env.NODE_ENV === 'production',
        minimizer: [
            // Minify JavaScript
            new TerserPlugin({
                terserOptions: {
                    compress: {
                        drop_console: process.env.NODE_ENV === 'production',
                    },
                    format: {
                        comments: false,
                    },
                },
                extractComments: false,
            }),
        ],
    },
};

