/**
 * Tests for Timeline Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen, fireEvent } from '@testing-library/react';
import Edit from '../../blocks/timeline/edit';

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
		Button: ({ children, onClick, ...rest }) =>
			React.createElement('button', { onClick, ...rest }, children),
		IconButton: ({ children, onClick, ...rest }) =>
			React.createElement('button', { onClick, ...rest }, children),
	};
});

describe('Timeline Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const emptyAttributes = {
		events: [],
		orientation: 'vertical',
	};

	const populatedAttributes = {
		events: [
			{
				date: '2020',
				title: 'Fundación',
				description: 'Se fundó la organización.',
				icon: 'dashicons-calendar',
				image: { url: '', alt: '', id: null },
			},
			{
				date: '2023',
				title: 'Expansión',
				description: 'Crecimiento a nivel nacional.',
				icon: '',
				image: {
					url: 'https://example.com/img.jpg',
					alt: 'Expansion',
					id: 2,
				},
			},
		],
		orientation: 'vertical',
	};

	test('renders empty state when no events exist', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(
			screen.getByText('No events added yet. Click "Add Event" to start.')
		).toBeInTheDocument();
	});

	test('renders Add Event button', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		expect(screen.getByText('Add Event')).toBeInTheDocument();
	});

	test('calls setAttributes when Add Event is clicked', () => {
		render(
			<Edit attributes={emptyAttributes} setAttributes={setAttributes} />
		);

		fireEvent.click(screen.getByText('Add Event'));
		expect(setAttributes).toHaveBeenCalledWith({
			events: [
				{
					date: '',
					title: '',
					description: '',
					icon: '',
					image: { url: '', alt: '', id: null },
				},
			],
		});
	});

	test('renders timeline events when provided', () => {
		const { container } = render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		const events = container.querySelectorAll('.timeline-event');
		expect(events).toHaveLength(2);
	});

	test('renders inspector controls with orientation setting', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
		expect(screen.getByText('Orientation')).toBeInTheDocument();
	});

	test('applies correct orientation class', () => {
		const { container } = render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
			/>
		);

		expect(
			container.querySelector('.timeline-vertical')
		).toBeInTheDocument();
	});
});
