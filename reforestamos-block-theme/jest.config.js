/**
 * Jest Configuration for Reforestamos Block Theme
 *
 * Configures Jest for testing WordPress/Gutenberg custom blocks
 * using @wordpress/jest-preset-default.
 *
 * @package Reforestamos
 */
module.exports = {
	testMatch: [
		'**/tests/**/*.test.js',
		'**/tests/**/*.test.jsx',
		'**/?(*.)+(spec|test).js',
		'**/?(*.)+(spec|test).jsx',
	],
	transform: {
		'^.+\\.[jt]sx?$':
			'<rootDir>/node_modules/@wordpress/scripts/config/babel-transform',
	},
	moduleNameMapper: {
		'\\.(css|less|scss|sass)$': '<rootDir>/tests/__mocks__/styleMock.js',
		'\\.(gif|ttf|eot|svg|png|jpg|jpeg|webp)$':
			'<rootDir>/tests/__mocks__/fileMock.js',
	},
	setupFilesAfterEnv: ['<rootDir>/tests/setup.js'],
	testEnvironment: 'jsdom',
	collectCoverageFrom: [
		'blocks/**/*.{js,jsx}',
		'src/**/*.{js,jsx}',
		'!**/node_modules/**',
		'!**/build/**',
		'!**/vendor/**',
	],
	coverageThreshold: {
		global: {
			branches: 70,
			functions: 70,
			lines: 70,
			statements: 70,
		},
	},
};
