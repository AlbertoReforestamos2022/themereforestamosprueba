/**
 * Tests for FAQs Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen } from '@testing-library/react';
import Edit from '../../blocks/faqs/edit';

// Mock @wordpress/element
jest.mock('@wordpress/element', () => ({
	...jest.requireActual('react'),
	useState: jest.requireActual('react').useState,
}));

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
		ToggleControl: ({ label, checked, onChange }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: 'checkbox',
					checked,
					onChange: (e) => onChange(e.target.checked),
				})
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
		Button: ({ children, onClick, disabled, ...rest }) =>
			React.createElement(
				'button',
				{ onClick, disabled, ...rest },
				children
			),
		TextareaControl: ({ label, value, onChange, placeholder }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('textarea', {
					value,
					onChange: (e) => onChange(e.target.value),
					placeholder,
				})
			),
	};
});

describe('FAQs Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const emptyAttributes = {
		faqs: [],
		openFirst: true,
		allowMultiple: false,
		accordionStyle: 'default',
	};

	const populatedAttributes = {
		faqs: [
			{
				question: '¿Qué es Reforestamos?',
				answer: 'Somos una organización ambiental.',
			},
			{
				question: '¿Cómo puedo ayudar?',
				answer: 'Puedes donar o ser voluntario.',
			},
		],
		openFirst: true,
		allowMultiple: false,
		accordionStyle: 'default',
	};

	test('renders empty state when no FAQs exist', () => {
		render(
			<Edit
				attributes={emptyAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(
			screen.getByText('No FAQs yet. Add your first FAQ above.')
		).toBeInTheDocument();
	});

	test('renders FAQ items when provided', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByText('Question 1')).toBeInTheDocument();
		expect(screen.getByText('Question 2')).toBeInTheDocument();
	});

	test('renders Add FAQ button', () => {
		render(
			<Edit
				attributes={emptyAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		const addButton = screen.getByText('Add FAQ');
		expect(addButton).toBeInTheDocument();
		expect(addButton).toBeDisabled();
	});

	test('renders preview section when FAQs exist', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByText('Preview')).toBeInTheDocument();
		// Questions appear in both editor and preview, so use getAllByText
		expect(
			screen.getAllByText('¿Qué es Reforestamos?').length
		).toBeGreaterThanOrEqual(1);
		expect(
			screen.getAllByText('¿Cómo puedo ayudar?').length
		).toBeGreaterThanOrEqual(1);
	});

	test('renders inspector controls with settings', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
	});
});
