/**
 * Tests for Hero Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen } from '@testing-library/react';
import Edit from '../../blocks/hero/edit';

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
		RangeControl: ({ label }) =>
			React.createElement('input', {
				'aria-label': label,
				type: 'range',
			}),
		TextControl: ({ label, value, onChange, ...rest }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: 'text',
					value: value || '',
					onChange: (e) => onChange(e.target.value),
					...rest,
				})
			),
		Button: ({ children, onClick, ...rest }) =>
			React.createElement('button', { onClick, ...rest }, children),
	};
});

describe('Hero Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const defaultAttributes = {
		title: '',
		subtitle: '',
		backgroundImage: { url: '', alt: '', id: null },
		buttonText: 'Conoce más',
		buttonUrl: '',
		overlayOpacity: 0.5,
		minHeight: '500px',
	};

	const populatedAttributes = {
		title: 'Reforestamos México',
		subtitle: 'Juntos por un México más verde',
		backgroundImage: {
			url: 'https://example.com/hero.jpg',
			alt: 'Hero background',
			id: 1,
		},
		buttonText: 'Conoce más',
		buttonUrl: 'https://example.com/about',
		overlayOpacity: 0.5,
		minHeight: '500px',
	};

	test('renders with default empty attributes', () => {
		const { container } = render(
			<Edit
				attributes={defaultAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(
			container.querySelector('.reforestamos-hero')
		).toBeInTheDocument();
	});

	test('renders hero overlay element', () => {
		const { container } = render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		const overlay = container.querySelector('.hero-overlay');
		expect(overlay).toBeInTheDocument();
		expect(overlay).toHaveStyle({ opacity: 0.5 });
	});

	test('renders hero content section', () => {
		const { container } = render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(container.querySelector('.hero-content')).toBeInTheDocument();
		expect(container.querySelector('.hero-title')).toBeInTheDocument();
		expect(container.querySelector('.hero-subtitle')).toBeInTheDocument();
	});

	test('applies background image style when image is set', () => {
		const { container } = render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		const heroBlock = container.querySelector('.reforestamos-hero');
		expect(heroBlock).toHaveStyle({
			backgroundImage: 'url(https://example.com/hero.jpg)',
		});
	});

	test('renders inspector controls', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
	});

	test('shows Select Image button when no background image', () => {
		render(
			<Edit
				attributes={defaultAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText('Select Image')).toBeInTheDocument();
	});

	test('shows Change Image button when background image exists', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText('Change Image')).toBeInTheDocument();
	});
});
