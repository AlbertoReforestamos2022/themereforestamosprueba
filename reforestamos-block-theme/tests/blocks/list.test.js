/**
 * Tests for List Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen } from '@testing-library/react';
import Edit from '../../blocks/list/edit';

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
		TextControl: ({ value, onChange, placeholder, onKeyPress }) =>
			React.createElement('input', {
				type: 'text',
				value: value || '',
				onChange: (e) => onChange(e.target.value),
				placeholder,
				onKeyPress,
			}),
		Button: ({ children, onClick, disabled, ...rest }) =>
			React.createElement(
				'button',
				{ onClick, disabled, ...rest },
				children
			),
	};
});

describe('List Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const emptyAttributes = {
		items: [],
		icon: 'check',
		listStyle: 'default',
		iconColor: 'primary',
	};

	const populatedAttributes = {
		items: [
			'Reforestación urbana',
			'Educación ambiental',
			'Conservación de bosques',
		],
		icon: 'leaf',
		listStyle: 'bordered',
		iconColor: 'primary',
	};

	test('renders empty state when no items exist', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(
			screen.getByText('No items yet. Add your first item above.')
		).toBeInTheDocument();
	});

	test('renders Add Item button', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(screen.getByText('Add Item')).toBeInTheDocument();
	});

	test('Add Item button is disabled when input is empty', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		const addButton = screen.getByText('Add Item');
		expect(addButton).toBeDisabled();
	});

	test('renders list items when provided', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText('Preview')).toBeInTheDocument();
		expect(screen.getByText('Reforestación urbana')).toBeInTheDocument();
		expect(screen.getByText('Educación ambiental')).toBeInTheDocument();
		expect(screen.getByText('Conservación de bosques')).toBeInTheDocument();
	});

	test('renders inspector controls with list settings', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
	});

	test('applies correct style class', () => {
		const { container } = render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(
			container.querySelector('.reforestamos-list--bordered')
		).toBeInTheDocument();
	});
});
