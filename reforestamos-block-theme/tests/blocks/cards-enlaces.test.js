/**
 * Tests for Cards-Enlaces Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen, fireEvent } from '@testing-library/react';
import Edit from '../../blocks/cards-enlaces/edit';

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
		RangeControl: ({ label, value, onChange, min, max }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: 'range',
					value,
					onChange: (e) => onChange(Number(e.target.value)),
					min,
					max,
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
		TextControl: ({ label, value, onChange, placeholder }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: 'text',
					value: value || '',
					onChange: (e) => onChange(e.target.value),
					placeholder,
				})
			),
		TextareaControl: ({ label, value, onChange, placeholder }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('textarea', {
					value: value || '',
					onChange: (e) => onChange(e.target.value),
					placeholder,
				})
			),
		Button: ({ children, onClick, ...rest }) =>
			React.createElement('button', { onClick, ...rest }, children),
	};
});

describe('Cards-Enlaces Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const emptyAttributes = {
		cards: [],
		columns: 3,
		cardStyle: 'default',
	};

	const populatedAttributes = {
		cards: [
			{
				title: 'Programas',
				description: 'Nuestros programas de reforestación',
				url: 'https://example.com/programas',
				icon: '🌳',
				image: { url: '', alt: '', id: null },
			},
			{
				title: 'Donaciones',
				description: 'Apoya nuestra causa',
				url: 'https://example.com/dona',
				icon: '💚',
				image: { url: '', alt: '', id: null },
			},
		],
		columns: 3,
		cardStyle: 'shadow',
	};

	test('renders empty state when no cards exist', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(
			screen.getByText(
				'No cards yet. Click "Add Card" to create your first card.'
			)
		).toBeInTheDocument();
	});

	test('renders Add Card button', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(screen.getByText('Add Card')).toBeInTheDocument();
	});

	test('calls setAttributes when Add Card is clicked', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		fireEvent.click(screen.getByText('Add Card'));
		expect(setAttributes).toHaveBeenCalledWith({
			cards: [
				{
					title: '',
					description: '',
					url: '',
					icon: '',
					image: { url: '', alt: '', id: null },
				},
			],
		});
	});

	test('renders card previews when cards exist', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText('Preview')).toBeInTheDocument();
		// Card titles appear in both editor header and preview, so use getAllByText
		expect(screen.getAllByText('Programas').length).toBeGreaterThanOrEqual(
			1
		);
		expect(screen.getAllByText('Donaciones').length).toBeGreaterThanOrEqual(
			1
		);
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

	test('displays column and style info', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText(/Columns:/)).toBeInTheDocument();
		expect(screen.getByText(/Style:/)).toBeInTheDocument();
	});
});
