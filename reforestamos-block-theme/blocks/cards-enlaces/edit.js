import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	SelectControl,
	TextControl,
	Button,
	TextareaControl,
} from '@wordpress/components';
import { useState } from '@wordpress/element';

const COLUMN_OPTIONS = [
	{ label: '2', value: 2 },
	{ label: '3', value: 3 },
	{ label: '4', value: 4 },
];

const STYLE_OPTIONS = [
	{ label: __('Default', 'reforestamos'), value: 'default' },
	{ label: __('Bordered', 'reforestamos'), value: 'bordered' },
	{ label: __('Shadow', 'reforestamos'), value: 'shadow' },
];

/**
 *
 * @param root0
 * @param root0.attributes
 * @param root0.setAttributes
 */
export default function Edit({ attributes, setAttributes }) {
	const { cards, columns, cardStyle } = attributes;
	const [editingCard, setEditingCard] = useState(null);

	const blockProps = useBlockProps({
		className: `reforestamos-cards-enlaces reforestamos-cards-enlaces--${cardStyle} reforestamos-cards-enlaces--cols-${columns}`,
	});

	const addCard = () => {
		const newCard = {
			title: '',
			description: '',
			url: '',
			icon: '',
			image: { url: '', alt: '', id: null },
		};
		setAttributes({ cards: [...cards, newCard] });
		setEditingCard(cards.length);
	};

	const removeCard = (index) => {
		const newCards = cards.filter((_, i) => i !== index);
		setAttributes({ cards: newCards });
		if (editingCard === index) {
			setEditingCard(null);
		}
	};

	const updateCard = (index, field, value) => {
		const newCards = [...cards];
		newCards[index] = { ...newCards[index], [field]: value };
		setAttributes({ cards: newCards });
	};

	const renderCardEditor = (card, index) => {
		const isEditing = editingCard === index;

		return (
			<div
				key={index}
				className="reforestamos-cards-enlaces__card-editor"
			>
				<div className="reforestamos-cards-enlaces__card-header">
					<h4>
						{card.title ||
							__('Card', 'reforestamos') + ' ' + (index + 1)}
					</h4>
					<div className="reforestamos-cards-enlaces__card-actions">
						<Button
							icon={isEditing ? 'arrow-up-alt2' : 'edit'}
							label={
								isEditing
									? __('Collapse', 'reforestamos')
									: __('Edit', 'reforestamos')
							}
							onClick={() =>
								setEditingCard(isEditing ? null : index)
							}
						/>
						<Button
							icon="trash"
							label={__('Remove', 'reforestamos')}
							onClick={() => removeCard(index)}
							isDestructive
						/>
					</div>
				</div>

				{isEditing && (
					<div className="reforestamos-cards-enlaces__card-fields">
						<TextControl
							label={__('Title', 'reforestamos')}
							value={card.title}
							onChange={(value) =>
								updateCard(index, 'title', value)
							}
							placeholder={__(
								'Enter card title…',
								'reforestamos'
							)}
						/>

						<TextareaControl
							label={__('Description', 'reforestamos')}
							value={card.description}
							onChange={(value) =>
								updateCard(index, 'description', value)
							}
							placeholder={__(
								'Enter card description…',
								'reforestamos'
							)}
							rows={3}
						/>

						<TextControl
							label={__('URL', 'reforestamos')}
							value={card.url}
							onChange={(value) =>
								updateCard(index, 'url', value)
							}
							placeholder={__('https://…', 'reforestamos')}
							type="url"
						/>

						<TextControl
							label={__('Icon (emoji or text)', 'reforestamos')}
							value={card.icon}
							onChange={(value) =>
								updateCard(index, 'icon', value)
							}
							placeholder={__('🌳 or icon name', 'reforestamos')}
							help={__(
								'Use an emoji or short text as icon',
								'reforestamos'
							)}
						/>

						<div className="reforestamos-cards-enlaces__image-upload">
							<label>
								{__('Image (optional)', 'reforestamos')}
							</label>
							<MediaUpload
								onSelect={(media) =>
									updateCard(index, 'image', {
										url: media.url,
										alt: media.alt,
										id: media.id,
									})
								}
								allowedTypes={['image']}
								value={card.image?.id}
								render={({ open }) => (
									<div className="reforestamos-cards-enlaces__image-control">
										{card.image?.url && (
											<div className="reforestamos-cards-enlaces__image-preview">
												<img
													src={card.image.url}
													alt={card.image.alt || ''}
												/>
											</div>
										)}
										<div className="reforestamos-cards-enlaces__image-buttons">
											<Button
												onClick={open}
												variant="secondary"
											>
												{card.image?.url
													? __(
															'Change Image',
															'reforestamos'
														)
													: __(
															'Select Image',
															'reforestamos'
														)}
											</Button>
											{card.image?.url && (
												<Button
													onClick={() =>
														updateCard(
															index,
															'image',
															{
																url: '',
																alt: '',
																id: null,
															}
														)
													}
													isDestructive
													variant="secondary"
												>
													{__(
														'Remove Image',
														'reforestamos'
													)}
												</Button>
											)}
										</div>
									</div>
								)}
							/>
						</div>
					</div>
				)}
			</div>
		);
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Cards Settings', 'reforestamos')}>
					<RangeControl
						label={__('Columns', 'reforestamos')}
						value={columns}
						onChange={(value) => setAttributes({ columns: value })}
						min={2}
						max={4}
						step={1}
					/>
					<SelectControl
						label={__('Card Style', 'reforestamos')}
						value={cardStyle}
						options={STYLE_OPTIONS}
						onChange={(value) =>
							setAttributes({ cardStyle: value })
						}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="reforestamos-cards-enlaces__editor">
					<div className="reforestamos-cards-enlaces__editor-header">
						<h3>{__('Cards Enlaces', 'reforestamos')}</h3>
						<p className="reforestamos-cards-enlaces__settings-info">
							{__('Columns:', 'reforestamos')} {columns} |{' '}
							{__('Style:', 'reforestamos')} {cardStyle}
						</p>
					</div>

					{cards.length > 0 && (
						<div className="reforestamos-cards-enlaces__cards-list">
							{cards.map((card, index) =>
								renderCardEditor(card, index)
							)}
						</div>
					)}

					<div className="reforestamos-cards-enlaces__add-card">
						<Button variant="primary" onClick={addCard} icon="plus">
							{__('Add Card', 'reforestamos')}
						</Button>
					</div>

					{cards.length === 0 && (
						<p className="reforestamos-cards-enlaces__empty">
							{__(
								'No cards yet. Click "Add Card" to create your first card.',
								'reforestamos'
							)}
						</p>
					)}
				</div>

				{cards.length > 0 && (
					<div className="reforestamos-cards-enlaces__preview">
						<h4>{__('Preview', 'reforestamos')}</h4>
						<div
							className={`reforestamos-cards-enlaces__grid reforestamos-cards-enlaces__grid--cols-${columns}`}
						>
							{cards.map((card, index) => (
								<div
									key={index}
									className="reforestamos-cards-enlaces__card"
								>
									{card.image?.url && (
										<div className="reforestamos-cards-enlaces__card-image">
											<img
												src={card.image.url}
												alt={
													card.image.alt || card.title
												}
											/>
										</div>
									)}
									<div className="reforestamos-cards-enlaces__card-content">
										{card.icon && (
											<div className="reforestamos-cards-enlaces__card-icon">
												{card.icon}
											</div>
										)}
										{card.title && (
											<h3 className="reforestamos-cards-enlaces__card-title">
												{card.title}
											</h3>
										)}
										{card.description && (
											<p className="reforestamos-cards-enlaces__card-description">
												{card.description}
											</p>
										)}
										{card.url && (
											<div className="reforestamos-cards-enlaces__card-link">
												<span>
													{__(
														'Learn more',
														'reforestamos'
													)}{' '}
													→
												</span>
											</div>
										)}
									</div>
								</div>
							))}
						</div>
					</div>
				)}
			</div>
		</>
	);
}
