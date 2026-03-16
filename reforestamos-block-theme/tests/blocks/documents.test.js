/**
 * Tests for Documents Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen } from '@testing-library/react';
import Edit from '../../blocks/documents/edit';

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
		TextControl: ({ label, value, onChange, placeholder, type }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: type || 'text',
					value: value || '',
					onChange: (e) => onChange(e.target.value),
					placeholder,
				})
			),
		Button: ({ children, onClick, ...rest }) =>
			React.createElement('button', { onClick, ...rest }, children),
		__experimentalNumberControl: ({ label, value, onChange }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: 'number',
					value: value || '',
					onChange: (e) => onChange(e.target.value),
				})
			),
	};
});

describe('Documents Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const emptyAttributes = {
		documents: [],
		category: '',
		sortBy: 'date',
		displayStyle: 'list',
	};

	const populatedAttributes = {
		documents: [
			{
				id: 1,
				title: 'Informe Anual 2023',
				description: 'Reporte de actividades',
				url: 'https://example.com/informe.pdf',
				fileType: 'pdf',
				fileSize: '2.5 MB',
				date: '2023-12-01',
				category: 'informes',
			},
			{
				id: 2,
				title: 'Guía de Reforestación',
				description: 'Manual práctico',
				url: 'https://example.com/guia.pdf',
				fileType: 'pdf',
				fileSize: '1.2 MB',
				date: '2023-06-15',
				category: 'guias',
			},
		],
		category: '',
		sortBy: 'date',
		displayStyle: 'list',
	};

	test('renders empty state when no documents exist', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(
			screen.getByText(
				'No documents added yet. Click "Add Document" to get started.'
			)
		).toBeInTheDocument();
	});

	test('renders Add Document button', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(screen.getByText('Add Document')).toBeInTheDocument();
	});

	test('renders document list when documents exist', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByText('Informe Anual 2023')).toBeInTheDocument();
		expect(screen.getByText('Guía de Reforestación')).toBeInTheDocument();
	});

	test('renders inspector controls with settings', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
	});

	test('displays file type badges in document preview', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		const pdfBadges = screen.getAllByText('PDF');
		expect(pdfBadges.length).toBeGreaterThanOrEqual(1);
	});

	test('renders documents header with title', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(screen.getByText('Documents')).toBeInTheDocument();
	});
});
