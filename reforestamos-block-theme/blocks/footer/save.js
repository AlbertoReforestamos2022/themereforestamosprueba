import { useBlockProps, RichText } from '@wordpress/block-editor';

/**
 *
 * @param root0
 * @param root0.attributes
 */
export default function Save({ attributes }) {
	const {
		columns,
		social,
		copyright,
		backgroundColor,
		textColor,
		columnCount,
		showSocial,
	} = attributes;

	const blockProps = useBlockProps.save({
		style: {
			backgroundColor,
			color: textColor,
		},
		className: 'reforestamos-footer',
	});

	return (
		<footer {...blockProps}>
			<div className="footer-content container">
				<div className="row">
					{columns
						.slice(0, columnCount)
						.map((column, columnIndex) => (
							<div
								key={columnIndex}
								className={`col-md-${12 / columnCount} col-sm-6 footer-column`}
							>
								<RichText.Content
									tagName="h4"
									value={column.title}
									className="footer-column-title"
								/>
								{column.content && (
									<RichText.Content
										tagName="p"
										value={column.content}
										className="footer-column-content"
									/>
								)}

								{column.links && column.links.length > 0 && (
									<ul className="footer-links">
										{column.links.map(
											(link, linkIndex) =>
												link.text &&
												link.url && (
													<li key={linkIndex}>
														<a
															href={link.url}
															className="footer-link"
														>
															{link.text}
														</a>
													</li>
												)
										)}
									</ul>
								)}
							</div>
						))}
				</div>

				{showSocial && social.some((item) => item.url) && (
					<div className="footer-social">
						<div className="social-links">
							{social.map(
								(item, index) =>
									item.url && (
										<a
											key={index}
											href={item.url}
											className="social-link"
											target="_blank"
											rel="noopener noreferrer"
											aria-label={item.name}
										>
											<i
												className={`fab fa-${item.icon}`}
											></i>
										</a>
									)
							)}
						</div>
					</div>
				)}

				<div className="footer-copyright">
					<RichText.Content
						tagName="p"
						value={copyright}
						className="copyright-text"
					/>
				</div>
			</div>
		</footer>
	);
}
