import { useBlockProps } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 */
export default function Save({ attributes }) {
	const { cards, columns, cardStyle } = attributes;

	const blockProps = useBlockProps.save({
		className: `reforestamos-cards-enlaces reforestamos-cards-enlaces--${cardStyle} reforestamos-cards-enlaces--cols-${columns}`,
	});

	if (!cards || cards.length === 0) {
		return null;
	}

	return (
		<div {...blockProps}>
			<div
				className={`reforestamos-cards-enlaces__grid reforestamos-cards-enlaces__grid--cols-${columns}`}
			>
				{cards.map((card, index) => {
					const CardWrapper = card.url ? 'a' : 'div';
					const cardProps = card.url
						? {
								href: card.url,
								className:
									'reforestamos-cards-enlaces__card reforestamos-cards-enlaces__card--link',
								rel: 'noopener noreferrer',
							}
						: {
								className: 'reforestamos-cards-enlaces__card',
							};

					return (
						<CardWrapper key={index} {...cardProps}>
							{card.image?.url && (
								<div className="reforestamos-cards-enlaces__card-image">
									<img
										src={card.image.url}
										alt={card.image.alt || card.title || ''}
										loading="lazy"
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
										<span>→</span>
									</div>
								)}
							</div>
						</CardWrapper>
					);
				})}
			</div>
		</div>
	);
}
