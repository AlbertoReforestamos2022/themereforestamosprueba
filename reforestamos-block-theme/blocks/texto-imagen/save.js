import { useBlockProps, RichText } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 */
export default function Save({ attributes }) {
	const { content, image, imagePosition, imageWidth } = attributes;

	const blockProps = useBlockProps.save({
		className: `reforestamos-texto-imagen image-${imagePosition}`,
	});

	// Calculate text width based on image width
	const imageWidthNumber = parseInt(imageWidth) || 50;
	const textWidth = `${100 - imageWidthNumber}%`;

	return (
		<div {...blockProps}>
			<div className="texto-imagen-container">
				<div
					className="texto-imagen-content"
					style={{ width: textWidth }}
				>
					<RichText.Content
						tagName="div"
						value={content}
						className="content-text"
					/>
				</div>

				{image.url && (
					<div
						className="texto-imagen-image"
						style={{ width: imageWidth }}
					>
						<img
							src={image.url}
							alt={image.alt || ''}
							loading="lazy"
						/>
					</div>
				)}
			</div>
		</div>
	);
}
