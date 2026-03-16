/**
 * Tests for Carousel Block (Edit component)
 *
 * Validates: Requirements 21.3
 *
 * @package Reforestamos
 */
import { render, screen } from '@testing-library/react';
import Edit from '../../blocks/carousel/edit';

// Mock @dnd-kit dependencies
jest.mock('@dnd-kit/core', () => ({
	DndContext: ({ children }) => children,
	closestCenter: jest.fn(),
}));
jest.mock('@dnd-kit/sortable', () => ({
	arrayMove: (arr, from, to) => {
		const result = [...arr];
		const [removed] = result.splice(from, 1);
		result.splice(to, 0, removed);
		return result;
	},
	SortableContext: ({ children }) => children,
	useSortable: () => ({
		attributes: {},
		listeners: {},
		setNodeRef: jest.fn(),
		transform: null,
		transition: null,
	}),
	verticalListSortingStrategy: jest.fn(),
}));
jest.mock('@dnd-kit/utilities', () => ({
	CSS: { Transform: { toString: () => '' } },
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
		RangeControl: ({ label }) =>
			React.createElement('input', {
				'aria-label': label,
				type: 'range',
			}),
		ToggleControl: ({ label, checked }) =>
			React.createElement(
				'label',
				null,
				label,
				React.createElement('input', {
					type: 'checkbox',
					checked,
					readOnly: true,
				})
			),
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

describe('Carousel Block - Edit', () => {
	const setAttributes = jest.fn();

	beforeEach(() => {
		setAttributes.mockClear();
	});

	const emptyAttributes = {
		images: [],
		autoplay: true,
		interval: 5000,
		showControls: true,
		showIndicators: true,
	};

	const populatedAttributes = {
		images: [
			{
				id: 1,
				url: 'https://example.com/img1.jpg',
				alt: 'Image 1',
				caption: 'Caption 1',
			},
			{
				id: 2,
				url: 'https://example.com/img2.jpg',
				alt: 'Image 2',
				caption: '',
			},
		],
		autoplay: true,
		interval: 5000,
		showControls: true,
		showIndicators: true,
	};

	test('renders placeholder when no images are selected', () => {
		render(
			<Edit
				attributes={emptyAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByTestId('placeholder')).toBeInTheDocument();
		expect(screen.getByText('Select Images')).toBeInTheDocument();
	});

	test('renders image list when images are provided', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		const images = screen.getAllByRole('img');
		expect(images).toHaveLength(2);
		expect(images[0]).toHaveAttribute(
			'src',
			'https://example.com/img1.jpg'
		);
		expect(images[1]).toHaveAttribute(
			'src',
			'https://example.com/img2.jpg'
		);
	});

	test('displays image count in preview info', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByText(/2/)).toBeInTheDocument();
		expect(screen.getByText(/images/)).toBeInTheDocument();
	});

	test('renders inspector controls with carousel settings', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByTestId('inspector-controls')).toBeInTheDocument();
	});

	test('shows Edit Gallery button when images exist', () => {
		render(
			<Edit
				attributes={populatedAttributes}
				setAttributes={setAttributes}
				clientId="test-id"
			/>
		);

		expect(screen.getByText('Edit Gallery')).toBeInTheDocument();
	});
});
