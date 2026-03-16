/**
 * Jest Test Setup
 *
 * Mocks WordPress globals and Gutenberg dependencies
 * so block components can be tested in isolation.
 *
 * @package Reforestamos
 */
import '@testing-library/jest-dom';

// Mock WordPress i18n
jest.mock('@wordpress/i18n', () => ({
	__: (str) => str,
	_x: (str) => str,
	_n: (single, plural, number) => (number === 1 ? single : plural),
	sprintf: (fmt, ...args) => {
		let i = 0;
		return fmt.replace(/%[sd]/g, () => args[i++]);
	},
}));

// Mock @wordpress/block-editor
jest.mock('@wordpress/block-editor', () => {
	const React = require('react');
	return {
		useBlockProps: (props = {}) => ({
			...props,
			className: `wp-block ${props.className || ''}`.trim(),
		}),
		InspectorControls: ({ children }) =>
			React.createElement(
				'div',
				{ 'data-testid': 'inspector-controls' },
				children
			),
		MediaUpload: ({ render }) => render({ open: jest.fn() }),
		MediaUploadCheck: ({ children }) =>
			React.createElement('div', null, children),
		RichText: Object.assign(
			({ tagName: Tag = 'div', value, onChange, placeholder, ...rest }) =>
				React.createElement(Tag, {
					...rest,
					contentEditable: true,
					suppressContentEditableWarning: true,
					dangerouslySetInnerHTML: { __html: value || '' },
					'data-placeholder': placeholder,
				}),
			{
				Content: ({ tagName: Tag = 'div', value, ...rest }) =>
					React.createElement(Tag, {
						...rest,
						dangerouslySetInnerHTML: { __html: value || '' },
					}),
			}
		),
	};
});
