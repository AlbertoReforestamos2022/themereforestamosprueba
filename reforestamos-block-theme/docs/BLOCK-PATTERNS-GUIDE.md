# Block Patterns User Guide

## What are Block Patterns?

Block patterns are pre-designed layouts that you can insert into your pages with a single click. They combine multiple blocks into cohesive designs that follow the Reforestamos brand guidelines.

## Available Patterns

### Headers

#### Hero Section
**Use for:** Homepage, landing pages, campaign pages
**Features:**
- Full-width background image
- Large title and subtitle
- Call-to-action button
- Customizable overlay

**Best practices:**
- Use high-quality, high-resolution images (1920x1080px minimum)
- Keep titles short and impactful (5-8 words)
- Use contrasting colors for readability

#### Page Header
**Use for:** Internal pages, blog posts, about pages
**Features:**
- Breadcrumb navigation
- Page title
- Light background

**Best practices:**
- Update breadcrumbs to match page hierarchy
- Keep titles descriptive but concise

### Content

#### Content with Image and Text
**Use for:** About sections, feature descriptions, service pages
**Features:**
- Two-column layout
- Image on one side, text on the other
- Vertically centered content
- CTA button

**Best practices:**
- Use images that relate to the content
- Keep paragraphs short (3-4 sentences)
- Use the CTA button to guide users to next steps

#### Testimonials
**Use for:** Social proof, volunteer stories, partner feedback
**Features:**
- Three-column grid
- Profile photos
- Quotes with attribution
- Responsive layout

**Best practices:**
- Use real photos of people
- Keep quotes authentic and specific
- Include full names and roles

#### Statistics
**Use for:** Impact pages, annual reports, campaign results
**Features:**
- Four-column layout
- Large numbers
- Descriptive labels
- Primary color background

**Best practices:**
- Use real, verifiable data
- Round numbers for readability (1.5M instead of 1,487,392)
- Update regularly to maintain credibility

### Call to Action

#### Call to Action
**Use for:** Conversion points, newsletter signups, event registration
**Features:**
- Centered layout
- Headline and description
- Primary and secondary buttons
- Light background

**Best practices:**
- Use action-oriented language ("Join now", "Get started")
- Keep descriptions benefit-focused
- Limit to 1-2 CTAs per section

### Team

#### Team Members
**Use for:** About page, team page, staff directory
**Features:**
- Three-column grid
- Profile photos
- Name, role, and bio
- Social media links

**Best practices:**
- Use consistent photo style (same background, lighting)
- Keep bios to 2-3 sentences
- Link to professional profiles (LinkedIn)

### Contact

#### Contact Section
**Use for:** Contact page, footer, support pages
**Features:**
- Contact information with icons
- Integrated contact form
- Social media links
- Office hours

**Best practices:**
- Keep contact information up to date
- Test the contact form regularly
- Include multiple contact methods

### Footers

#### Complete Footer
**Use for:** Site-wide footer
**Features:**
- Four-column layout
- Quick links
- Contact information
- Social media
- Copyright notice

**Best practices:**
- Update copyright year annually
- Keep link lists organized and relevant
- Test all links regularly

## How to Use Patterns

### Inserting a Pattern

1. **Open the Block Editor**
   - Edit any page or post
   - Click the "+" button in the top left

2. **Find Patterns**
   - Click the "Patterns" tab
   - Browse by category or search

3. **Insert Pattern**
   - Click on the pattern you want
   - It will be inserted at your cursor position

4. **Customize Content**
   - Click on any text to edit
   - Click on images to replace
   - Adjust colors and spacing as needed

### Customizing Patterns

#### Changing Text
- Click on any text element
- Type your new content
- Use the toolbar to format (bold, italic, etc.)

#### Replacing Images
- Click on an image
- Click "Replace" in the toolbar
- Choose from Media Library or upload new

#### Adjusting Colors
- Select a block
- Open the block settings panel (right sidebar)
- Choose colors from the theme palette

#### Modifying Layout
- Click on a column or group
- Use the toolbar to adjust width
- Drag blocks to reorder

#### Adding/Removing Blocks
- Click the "+" button within a pattern
- Select a block to add
- Click the three dots on any block → Remove to delete

## Pattern Combinations

### Homepage Layout
1. Hero Section
2. Statistics
3. Content with Image and Text
4. Testimonials
5. Call to Action
6. Complete Footer

### About Page Layout
1. Page Header
2. Content with Image and Text
3. Team Members
4. Statistics
5. Call to Action

### Contact Page Layout
1. Page Header
2. Contact Section
3. Complete Footer

## Tips and Best Practices

### Design Consistency
- Use patterns as starting points
- Maintain consistent spacing between sections
- Stick to the theme color palette
- Use the same button styles throughout

### Content Strategy
- Lead with benefits, not features
- Use active voice
- Keep paragraphs short
- Include clear calls to action

### Image Guidelines
- Use high-quality images (minimum 1200px wide)
- Optimize images before uploading (use WebP format)
- Include descriptive alt text for accessibility
- Use images that reflect diversity and inclusion

### Accessibility
- Ensure sufficient color contrast
- Use descriptive link text (avoid "click here")
- Include alt text for all images
- Test with keyboard navigation

### Mobile Optimization
- Preview on mobile before publishing
- Ensure text is readable on small screens
- Test touch targets (buttons, links)
- Check image loading on mobile

## Troubleshooting

### Pattern Not Appearing
- Clear browser cache
- Refresh the editor
- Check if theme is active
- Verify pattern files exist in `patterns/` directory

### Pattern Looks Different After Insert
- Check if custom CSS is interfering
- Verify theme.json colors are loaded
- Clear site cache
- Test in a different browser

### Can't Edit Pattern Content
- Make sure you're clicking on the specific block
- Check if block is locked (unlock in settings)
- Try selecting parent block first

### Images Not Loading
- Check file permissions
- Verify image URLs are correct
- Test image in Media Library
- Check for broken links

## Advanced Usage

### Creating Custom Patterns
See the developer documentation in `TASK-36-IMPLEMENTATION.md` for instructions on creating custom patterns.

### Exporting Patterns
1. Go to Appearance → Pattern Manager
2. Select a pattern
3. Click "Export Pattern"
4. Save the JSON file

### Importing Patterns
1. Go to Appearance → Pattern Manager
2. Click "Choose File"
3. Select a JSON pattern file
4. Click "Import Pattern"

### Sharing Patterns
- Export patterns as JSON files
- Share with other Reforestamos sites
- Contribute to the pattern library
- Document custom patterns for team

## Support

For questions or issues with block patterns:
- Check this guide first
- Review the implementation documentation
- Contact the development team
- Submit feedback for improvements

## Updates

This guide is updated regularly. Last updated: 2024

---

**Remember:** Patterns are starting points. Feel free to customize them to match your specific needs while maintaining the Reforestamos brand identity.
