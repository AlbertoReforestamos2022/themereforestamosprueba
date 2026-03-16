/**
 * Tests for Texto-Imagen Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen } from '@testing-library/react';
import Edit from '../../blocks/texto-imagen/edit';

// Mock @wordpress/components
jest.mock('@wordpress/components', () => {
	const React = require('react');
	return {
		PanelBody: ({ title, children }) =>
			React.createElement(
				'div',
				{ 'data-testid': 'panel-body', title },
				children
			),
		SelectControl: ({ label, value, options, onChange }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement(
					'select',
					{ value, onChange: (e) => onChange(e.target.value) },
					options.map((opt) =>
						React.createElement(
							'option',
							{ key: opt.value, value: opt.value },
							opt.label
						)
					)
				)
			),
		RangeControl: ({ label }) =>
			React.createElement('input', {
				'aria-label': label,
				type: 'range',
			}),
		Button: ({ children, onClick, ...rest }) =>
			React.createElement('button', { onClick, ...rest }, children),
		Placeholder: ({ label, instructions, children }) =>
			React.createElement(
				'div',
				{ 'data-testid': 'placeholder' },
				React.createElement('span', null, label),
				React.createElement('span', null, instructions),
				children
			),
	};
});

describe('Texto-Imagen Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const noImageAttributes = {
		content: '',
		image: { url: '', alt: '', id: null },
		imagePosition: 'right',
		imageWidth: '50%',
	};

	const withImageAttributes = {
		content: '<p>Texto de ejemplo</p>',
		image: {
			url: 'https://example.com/photo.jpg',
			alt: 'Foto de ejemplo',
			id: 1,
		},
		imagePosition: 'left',
		imageWidth: '40%',
	};

	test('renders with correct image position class', () => {
		const { container } = render(
			<Edit
				attributes={noImageAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(
			container.querySelector('.reforestamos-texto-imagen.image-right')
		).toBeInTheDocument();
	});

	test('renders placeholder when no image is selected', () => {
		render(
			<Edit
				attributes={noImageAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByTestId('placeholder')).toBeInTheDocument();
		expect(screen.getByText('Select Image')).toBeInTheDocument();
	});

	test('renders image when provided', () => {
		const { container } = render(
			<Edit
				attributes={withImageAttributes}
				setAttributes={setAttributes}
			/>
		);

		const img = container.querySelector('img');
		expect(img).toBeInTheDocument();
		expect(img).toHaveAttribute('src', 'https://example.com/photo.jpg');
	});

	test('renders Replace and Remove buttons when image exists', () => {
		render(
			<Edit
				attributes={withImageAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText('Replace')).toBeInTheDocument();
		expect(screen.getByText('Remove')).toBeInTheDocument();
	});

	test('renders inspector controls with layout settings', () => {
		render(
			<Edit
				attributes={withImageAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
		expect(screen.getByText('Image Position')).toBeInTheDocument();
	});

	test('applies left image position class', () => {
		const { container } = render(
			<Edit
				attributes={withImageAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(
			container.querySelector('.reforestamos-texto-imagen.image-left')
		).toBeInTheDocument();
	});
});
