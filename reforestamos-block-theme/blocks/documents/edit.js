import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, SelectControl, Button, TextControl, __experimentalNumberControl as NumberControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function Edit({ attributes, setAttributes }) {
    const { documents, category, sortBy, displayStyle } = attributes;
    const [editingIndex, setEditingIndex] = useState(null);

    const blockProps = useBlockProps({
        className: 'reforestamos-documents'
    });

    const addDocument = () => {
        const newDocuments = [...documents, {
            id: Date.now(),
            title: '',
            description: '',
            url: '',
            fileType: 'pdf',
            fileSize: '',
            date: new Date().toISOString().split('T')[0],
            category: ''
        }];
        setAttributes({ documents: newDocuments });
        setEditingIndex(newDocuments.length - 1);
    };

    const updateDocument = (index, field, value) => {
        const newDocuments = [...documents];
        newDocuments[index] = { ...newDocuments[index], [field]: value };
        setAttributes({ documents: newDocuments });
    };

    const removeDocument = (index) => {
        const newDocuments = documents.filter((_, i) => i !== index);
        setAttributes({ documents: newDocuments });
        setEditingIndex(null);
    };

    const getFileIcon = (fileType) => {
        const icons = {
            pdf: '📄',
            doc: '📝',
            docx: '📝',
            xls: '📊',
            xlsx: '📊',
            ppt: '📽️',
            pptx: '📽️',
            zip: '🗜️',
            default: '📎'
        };
        return icons[fileType.toLowerCase()] || icons.default;
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Document Settings', 'reforestamos')}>
                    <SelectControl
                        label={__('Display Style', 'reforestamos')}
                        value={displayStyle}
                        options={[
                            { label: __('List', 'reforestamos'), value: 'list' },
                            { label: __('Grid', 'reforestamos'), value: 'grid' }
                        ]}
                        onChange={(value) => setAttributes({ displayStyle: value })}
                    />
                    <SelectControl
                        label={__('Sort By', 'reforestamos')}
                        value={sortBy}
                        options={[
                            { label: __('Date', 'reforestamos'), value: 'date' },
                            { label: __('Name', 'reforestamos'), value: 'name' },
                            { label: __('Type', 'reforestamos'), value: 'type' },
                            { label: __('Size', 'reforestamos'), value: 'size' }
                        ]}
                        onChange={(value) => setAttributes({ sortBy: value })}
                    />
                    <TextControl
                        label={__('Filter by Category', 'reforestamos')}
                        value={category}
                        onChange={(value) => setAttributes({ category: value })}
                        help={__('Leave empty to show all categories', 'reforestamos')}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div className="documents-editor">
                    <div className="documents-header">
                        <h3>{__('Documents', 'reforestamos')}</h3>
                        <Button 
                            variant="primary" 
                            onClick={addDocument}
                        >
                            {__('Add Document', 'reforestamos')}
                        </Button>
                    </div>

                    {documents.length === 0 ? (
                        <div className="documents-empty">
                            <p>{__('No documents added yet. Click "Add Document" to get started.', 'reforestamos')}</p>
                        </div>
                    ) : (
                        <div className={`documents-list ${displayStyle}`}>
                            {documents.map((doc, index) => (
                                <div key={doc.id} className="document-item">
                                    {editingIndex === index ? (
                                        <div className="document-edit">
                                            <TextControl
                                                label={__('Title', 'reforestamos')}
                                                value={doc.title}
                                                onChange={(value) => updateDocument(index, 'title', value)}
                                            />
                                            <TextControl
                                                label={__('Description', 'reforestamos')}
                                                value={doc.description}
                                                onChange={(value) => updateDocument(index, 'description', value)}
                                            />
                                            <MediaUpload
                                                onSelect={(media) => {
                                                    updateDocument(index, 'url', media.url);
                                                    updateDocument(index, 'fileType', media.subtype || 'pdf');
                                                    updateDocument(index, 'fileSize', media.filesizeHumanReadable || '');
                                                }}
                                                allowedTypes={['application', 'application/pdf']}
                                                value={doc.url}
                                                render={({ open }) => (
                                                    <Button onClick={open} variant="secondary">
                                                        {doc.url ? __('Change File', 'reforestamos') : __('Select File', 'reforestamos')}
                                                    </Button>
                                                )}
                                            />
                                            {doc.url && (
                                                <p className="file-info">
                                                    <small>{doc.url}</small>
                                                </p>
                                            )}
                                            <SelectControl
                                                label={__('File Type', 'reforestamos')}
                                                value={doc.fileType}
                                                options={[
                                                    { label: 'PDF', value: 'pdf' },
                                                    { label: 'DOC', value: 'doc' },
                                                    { label: 'DOCX', value: 'docx' },
                                                    { label: 'XLS', value: 'xls' },
                                                    { label: 'XLSX', value: 'xlsx' },
                                                    { label: 'PPT', value: 'ppt' },
                                                    { label: 'PPTX', value: 'pptx' },
                                                    { label: 'ZIP', value: 'zip' }
                                                ]}
                                                onChange={(value) => updateDocument(index, 'fileType', value)}
                                            />
                                            <TextControl
                                                label={__('File Size', 'reforestamos')}
                                                value={doc.fileSize}
                                                onChange={(value) => updateDocument(index, 'fileSize', value)}
                                                placeholder="e.g., 2.5 MB"
                                            />
                                            <TextControl
                                                label={__('Date', 'reforestamos')}
                                                type="date"
                                                value={doc.date}
                                                onChange={(value) => updateDocument(index, 'date', value)}
                                            />
                                            <TextControl
                                                label={__('Category', 'reforestamos')}
                                                value={doc.category}
                                                onChange={(value) => updateDocument(index, 'category', value)}
                                            />
                                            <div className="document-actions">
                                                <Button 
                                                    variant="primary" 
                                                    onClick={() => setEditingIndex(null)}
                                                >
                                                    {__('Done', 'reforestamos')}
                                                </Button>
                                                <Button 
                                                    variant="secondary" 
                                                    isDestructive
                                                    onClick={() => removeDocument(index)}
                                                >
                                                    {__('Remove', 'reforestamos')}
                                                </Button>
                                            </div>
                                        </div>
                                    ) : (
                                        <div className="document-preview" onClick={() => setEditingIndex(index)}>
                                            <span className="document-icon">{getFileIcon(doc.fileType)}</span>
                                            <div className="document-info">
                                                <h4>{doc.title || __('Untitled Document', 'reforestamos')}</h4>
                                                <p>{doc.description}</p>
                                                <div className="document-meta">
                                                    <span className="file-type">{doc.fileType.toUpperCase()}</span>
                                                    {doc.fileSize && <span className="file-size">{doc.fileSize}</span>}
                                                    {doc.date && <span className="file-date">{doc.date}</span>}
                                                </div>
                                            </div>
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </>
    );
}
