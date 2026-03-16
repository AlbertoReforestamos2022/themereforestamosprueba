/**
 * ESLint Configuration for Reforestamos México
 *
 * Uses WordPress JavaScript Coding Standards.
 *
 * @package Reforestamos
 */
module.exports = {
	extends: [
		'plugin:@wordpress/eslint-plugin/recommended',
		'prettier',
	],
	env: {
		browser: true,
		es6: true,
		node: true,
		jquery: true,
	},
	globals: {
		wp: 'readonly',
		jQuery: 'readonly',
		ajaxurl: 'readonly',
		reforestamosData: 'readonly',
		bootstrap: 'readonly',
		reforestamosContactForm: 'readonly',
		reforestamosConsent: 'readonly',
		reforestamosEventReg: 'readonly',
		reforestamosI18n: 'readonly',
		GLightbox: 'readonly',
		gtag: 'readonly',
		reforestamosCommAdmin: 'readonly',
		reforestamosChatbot: 'readonly',
		reforestamosComm: 'readonly',
		reforestamosCom: 'readonly',
		reforestamosAdmin: 'readonly',
		reforestamosComp: 'readonly',
		reforestamosEmpresas: 'readonly',
		monthlyClicksData: 'readonly',
		Chart: 'readonly',
		L: 'readonly',
	},
	rules: {
		// Allow console.warn and console.error in development
		'no-console': [ 'warn', { allow: [ 'warn', 'error' ] } ],
		// Relax JSDoc rules
		'jsdoc/require-jsdoc': 'off',
		'jsdoc/require-param-type': 'off',
		'jsdoc/empty-tags': 'off',
		// Relax unused vars (warn instead of error)
		'no-unused-vars': [ 'warn', { argsIgnorePattern: '^_' } ],
		// Relax WordPress-specific rules
		'@wordpress/no-unused-vars-before-return': 'off',
		'@wordpress/no-unsafe-wp-apis': 'off',
		'@wordpress/i18n-ellipsis': 'off',
		// Relax accessibility rules (warn instead of error)
		'jsx-a11y/label-has-associated-control': 'warn',
		'jsx-a11y/click-events-have-key-events': 'warn',
		'jsx-a11y/no-static-element-interactions': 'warn',
		// Allow nested ternary
		'no-nested-ternary': 'off',
		// Allow import flexibility
		'import/no-extraneous-dependencies': 'off',
		// Allow variable shadowing in closures
		'no-shadow': 'off',
		// Relax WP-specific active element rule
		'@wordpress/no-global-active-element': 'off',
		// Allow alert/confirm/prompt in admin scripts
		'no-alert': 'off',
		// Allow var in legacy code
		'no-var': 'warn',
		// Allow flexible array callbacks
		'array-callback-return': 'warn',
	},
	overrides: [
		{
			// Block editor files use JSX
			files: [ '**/blocks/**/*.js' ],
			parserOptions: {
				ecmaFeatures: {
					jsx: true,
				},
			},
		},
		{
			// Test files
			files: [ '**/*.test.js', '**/*.spec.js', '**/tests/setup.js' ],
			env: {
				jest: true,
			},
		},
	],
	ignorePatterns: [
		'node_modules/',
		'build/',
		'dist/',
		'vendor/',
		'CMB2/',
		'EjemploTheme/',
		'*.min.js',
	],
};
