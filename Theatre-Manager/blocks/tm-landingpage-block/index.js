/**
 * Theatre Manager - Show Landing Page Block
 * 
 * Vanilla JS implementation for Gutenberg block
 * Provides UI for tm_landingpage shortcode configuration
 */

(function() {
    const el = wp.element.createElement;
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, BlockControls } = wp.blockEditor;
    const { 
        PanelBody, TextControl, CheckboxControl, SelectControl, Button, Spinner, ColorPalette
    } = wp.components;
    const { useState, useEffect } = wp.element;

    const AVAILABLE_FIELDS = [
        { id: 'show_name', label: 'Show Name', description: 'Title of the show' },
        { id: 'show_image', label: 'Show Image', description: 'Show poster/main image' },
        { id: 'author', label: 'Author', description: 'Primary playwright' },
        { id: 'sub_authors', label: 'Co-writers', description: 'Additional authors' },
        { id: 'director', label: 'Director', description: 'Director name' },
        { id: 'associate_director', label: 'Assistant Director', description: 'Associate/Assistant director' },
        { id: 'producer', label: 'Producer', description: 'Producer name' },
        { id: 'stage_manager', label: 'Stage Manager', description: 'Stage manager name' },
        { id: 'synopsis', label: 'Synopsis', description: 'Show description' },
        { id: 'show_dates', label: 'Show Dates', description: 'Performance dates and times' },
        { id: 'ticket_url', label: 'Ticket URL', description: 'Link to purchase tickets' },
        { id: 'cast', label: 'Cast List', description: 'Simple cast member list' },
        { id: 'castwithbio', label: 'Cast with Photos', description: 'Cast in responsive grid with photos' },
        { id: 'venue', label: 'Venue', description: 'Theatre location info' }
    ];

    const DEFAULT_FIELDS = ['show_name', 'show_image', 'author', 'director', 'producer', 'stage_manager', 'synopsis', 'show_dates', 'ticket_url', 'castwithbio', 'venue'];

    const BUTTON_FORMATS = [
        { label: 'Default', value: 'default' },
        { label: 'Modern', value: 'modern' },
        { label: 'Minimal', value: 'minimal' },
        { label: 'Outline', value: 'outline' },
        { label: 'Gradient', value: 'gradient' },
        { label: 'Prominent', value: 'prominent' },
        { label: 'Success', value: 'success' },
        { label: 'Ghost', value: 'ghost' },
        { label: 'Glass', value: 'glass' }
    ];

    registerBlockType('theatre-manager/landingpage', {
        title: 'Show Landing Page',
        description: 'Display comprehensive information about a theatre show',
        category: 'common',
        icon: 'theater',
        keywords: ['show', 'theatre', 'landing', 'cast', 'venue'],
        
        attributes: {
            showId: { type: 'string', default: 'current' },
            showTitle: { type: 'string', default: 'Current Show' },
            fieldList: { type: 'array', default: DEFAULT_FIELDS },
            castcols: { type: 'number', default: 3 },
            urlbutton: { type: 'boolean', default: true },
            buttonformat: { type: 'string', default: 'default' },
            searchValue: { type: 'string', default: '' },
            textColor: { type: 'string', default: '#333333' },
            backgroundColor: { type: 'string', default: '#ffffff' },
            accentColor: { type: 'string', default: '#0073aa' },
            headingColor: { type: 'string', default: '#1a1a1a' }
        },

        edit: function(props) {
            const { attributes, setAttributes } = props;
            const [shows, setShows] = useState([]);
            const [loading, setLoading] = useState(false);
            const [searchTerm, setSearchTerm] = useState(attributes.searchValue);
            const [filteredShows, setFilteredShows] = useState([]);
            const [showResults, setShowResults] = useState(false);
            const [draggedField, setDraggedField] = useState(null);

            useEffect(() => {
                setLoading(true);
                fetch('/wp-json/wp/v2/show?per_page=100')
                    .then(res => res.json())
                    .then(data => {
                        if (Array.isArray(data)) {
                            setShows(data);
                        }
                        setLoading(false);
                    })
                    .catch(() => setLoading(false));
            }, []);

            useEffect(() => {
                if (searchTerm) {
                    const filtered = shows.filter(show =>
                        show.title.rendered.toLowerCase().includes(searchTerm.toLowerCase())
                    );
                    setFilteredShows(filtered);
                    setShowResults(true);
                } else {
                    setShowResults(false);
                }
            }, [searchTerm, shows]);

            const selectShow = (showId, showTitle) => {
                setAttributes({ showId: showId.toString(), showTitle });
                setSearchTerm('');
                setShowResults(false);
            };

            const toggleField = (fieldId) => {
                const currentFields = attributes.fieldList || [];
                if (currentFields.includes(fieldId)) {
                    setAttributes({ fieldList: currentFields.filter(f => f !== fieldId) });
                } else {
                    setAttributes({ fieldList: [...currentFields, fieldId] });
                }
            };

            const moveField = (fromIndex, toIndex) => {
                const newFields = [...(attributes.fieldList || [])];
                const [moved] = newFields.splice(fromIndex, 1);
                newFields.splice(toIndex, 0, moved);
                setAttributes({ fieldList: newFields });
            };

            const shortcodeText = `[tm_landingpage show_id="${attributes.showId}" field_list="${(attributes.fieldList || []).join(',')}" castcols="${attributes.castcols}" urlbutton="${attributes.urlbutton ? 'true' : 'false'}" buttonformat="${attributes.buttonformat}" text_color="${attributes.textColor}" bg_color="${attributes.backgroundColor}" accent_color="${attributes.accentColor}" heading_color="${attributes.headingColor}"]`;

            return el('div', { className: 'tm-landingpage-block-editor' },
                el(InspectorControls, null,
                    el(PanelBody, { title: 'Show Selection', initialOpen: true },
                        el('div', { className: 'tm-show-search-wrapper' },
                            el(TextControl, {
                                label: 'Search Show',
                                value: searchTerm,
                                onChange: (value) => setSearchTerm(value),
                                placeholder: 'Type show name...'
                            }),
                            loading && el(Spinner),
                            showResults && filteredShows.length > 0 && el('div', { className: 'tm-show-results' },
                                filteredShows.slice(0, 5).map(show =>
                                    el('div', {
                                        key: show.id,
                                        className: 'tm-show-result-item',
                                        onClick: () => selectShow(show.id, show.title.rendered)
                                    }, show.title.rendered)
                                )
                            ),
                            el(Button, {
                                isSecondary: true,
                                onClick: () => selectShow('current', 'Current Show'),
                                style: { marginTop: '8px' }
                            }, 'Use Current Show')
                        ),
                        attributes.showId && el('div', {
                            style: { marginTop: '10px', padding: '8px', background: '#f0f0f0', borderRadius: '4px' }
                        },
                            el('strong', null, 'Selected: '), attributes.showTitle
                        )
                    ),
                    el(PanelBody, { title: 'Color Settings', initialOpen: false },
                        el('div', null,
                            el('p', { style: { fontSize: '12px', marginBottom: '12px' } }, 'Choose colors for the landing page display:'),
                            el('div', { style: { marginBottom: '16px' } },
                                el('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '500', fontSize: '13px' } }, 'Heading Color'),
                                el(ColorPalette, {
                                    colors: [
                                        { name: 'Black', color: '#1a1a1a' },
                                        { name: 'Dark Gray', color: '#333333' },
                                        { name: 'Blue', color: '#0073aa' },
                                        { name: 'Purple', color: '#6f2da8' },
                                        { name: 'Dark Green', color: '#174e3e' }
                                    ],
                                    value: attributes.headingColor,
                                    onChange: (color) => setAttributes({ headingColor: color })
                                })
                            ),
                            el('div', { style: { marginBottom: '16px' } },
                                el('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '500', fontSize: '13px' } }, 'Text Color'),
                                el(ColorPalette, {
                                    colors: [
                                        { name: 'Black', color: '#000000' },
                                        { name: 'Dark Gray', color: '#333333' },
                                        { name: 'Gray', color: '#666666' },
                                        { name: 'Navy Blue', color: '#0a3a4a' },
                                        { name: 'Dark Brown', color: '#3d2817' }
                                    ],
                                    value: attributes.textColor,
                                    onChange: (color) => setAttributes({ textColor: color })
                                })
                            ),
                            el('div', { style: { marginBottom: '16px' } },
                                el('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '500', fontSize: '13px' } }, 'Accent Color (Links & Highlights)'),
                                el(ColorPalette, {
                                    colors: [
                                        { name: 'Blue', color: '#0073aa' },
                                        { name: 'Green', color: '#00a32a' },
                                        { name: 'Purple', color: '#6f2da8' },
                                        { name: 'Orange', color: '#d97f3a' },
                                        { name: 'Red', color: '#c63d43' }
                                    ],
                                    value: attributes.accentColor,
                                    onChange: (color) => setAttributes({ accentColor: color })
                                })
                            ),
                            el('div', { style: { marginBottom: '0' } },
                                el('label', { style: { display: 'block', marginBottom: '8px', fontWeight: '500', fontSize: '13px' } }, 'Background Color'),
                                el(ColorPalette, {
                                    colors: [
                                        { name: 'White', color: '#ffffff' },
                                        { name: 'Off-White', color: '#f9f9f9' },
                                        { name: 'Light Gray', color: '#f5f5f5' },
                                        { name: 'Cream', color: '#faf7f2' },
                                        { name: 'Light Blue', color: '#f0f7ff' }
                                    ],
                                    value: attributes.backgroundColor,
                                    onChange: (color) => setAttributes({ backgroundColor: color })
                                })
                            )
                        )
                    ),
                    el(PanelBody, { title: 'Field Selection', initialOpen: true },
                        el('div', { className: 'tm-field-selector' },
                            el('p', { style: { fontSize: '12px', color: '#666' } }, 'Check fields to include. Drag to reorder.'),
                            el('div', { className: 'tm-fields-list' },
                                AVAILABLE_FIELDS.map((field, index) =>
                                    el('div', {
                                        key: field.id,
                                        className: `tm-field-item ${attributes.fieldList?.includes(field.id) ? 'active' : ''}`,
                                        draggable: attributes.fieldList?.includes(field.id),
                                        onDragStart: () => setDraggedField(index),
                                        onDragOver: (e) => e.preventDefault(),
                                        onDrop: () => {
                                            if (draggedField !== null && draggedField !== index) {
                                                moveField(draggedField, index);
                                                setDraggedField(null);
                                            }
                                        }
                                    },
                                        el(CheckboxControl, {
                                            label: field.label,
                                            help: field.description,
                                            checked: attributes.fieldList?.includes(field.id) || false,
                                            onChange: () => toggleField(field.id)
                                        })
                                    )
                                )
                            )
                        )
                    ),
                    attributes.fieldList?.includes('castwithbio') && el(PanelBody, { title: 'Cast Options', initialOpen: false },
                        el(TextControl, {
                            label: 'Cast Columns',
                            type: 'number',
                            min: '1',
                            max: '6',
                            value: attributes.castcols,
                            onChange: (value) => setAttributes({ castcols: parseInt(value) || 3 }),
                            help: 'Number of columns for cast grid (1-6)'
                        })
                    ),
                    attributes.fieldList?.includes('ticket_url') && el(PanelBody, { title: 'Ticket Button Options', initialOpen: false },
                        el(CheckboxControl, {
                            label: 'Display as Button',
                            checked: attributes.urlbutton,
                            onChange: (value) => setAttributes({ urlbutton: value })
                        }),
                        attributes.urlbutton && el(SelectControl, {
                            label: 'Button Style',
                            value: attributes.buttonformat,
                            options: BUTTON_FORMATS,
                            onChange: (value) => setAttributes({ buttonformat: value })
                        })
                    )
                ),
                el('div', { className: 'tm-landingpage-block-preview' },
                    el('div', {
                        style: { 
                            padding: '20px', 
                            background: attributes.backgroundColor, 
                            border: `2px solid ${attributes.accentColor}`, 
                            borderRadius: '4px',
                            color: attributes.textColor
                        }
                    },
                        el('div', {
                            style: {
                                marginBottom: '20px',
                                padding: '15px',
                                background: 'rgba(0,0,0,0.03)',
                                borderLeft: `4px solid ${attributes.accentColor}`,
                                borderRadius: '4px'
                            }
                        },
                            el('label', { style: { fontSize: '11px', color: '#666', fontWeight: '600', display: 'block', marginBottom: '8px' } }, 'SHOW SELECTION'),
                            attributes.showId ? 
                                el('div', null,
                                    el('div', { style: { fontSize: '16px', fontWeight: '600', color: attributes.headingColor, marginBottom: '8px' } }, attributes.showTitle),
                                    el('div', null,
                                        el(Button, {
                                            isSecondary: true,
                                            onClick: () => setSearchTerm(''),
                                            style: { marginTop: '8px' }
                                        }, 'Change Show')
                                    )
                                )
                                :
                                el('div', null,
                                    el('div', { style: { fontSize: '13px', color: '#666', marginBottom: '12px' } }, 'Click below to select a show:'),
                                    el('div', null,
                                        el(TextControl, {
                                            label: '',
                                            value: searchTerm,
                                            onChange: (value) => setSearchTerm(value),
                                            placeholder: 'Type show name...',
                                            style: { margin: '0' }
                                        })
                                    ),
                                    loading && el(Spinner),
                                    showResults && filteredShows.length > 0 && el('div', { 
                                        style: { 
                                            marginTop: '8px',
                                            background: '#fff',
                                            border: `1px solid ${attributes.accentColor}`,
                                            borderRadius: '4px',
                                            maxHeight: '150px',
                                            overflow: 'auto'
                                        } 
                                    },
                                        filteredShows.slice(0, 5).map(show =>
                                            el('div', {
                                                key: show.id,
                                                style: {
                                                    padding: '10px 12px',
                                                    borderBottom: '1px solid #eee',
                                                    cursor: 'pointer',
                                                    fontSize: '13px',
                                                    transition: 'background-color 0.2s'
                                                },
                                                onMouseEnter: (e) => e.target.style.backgroundColor = `${attributes.accentColor}22`,
                                                onMouseLeave: (e) => e.target.style.backgroundColor = 'transparent',
                                                onClick: () => selectShow(show.id, show.title.rendered)
                                            }, show.title.rendered)
                                        )
                                    ),
                                    el(Button, {
                                        isPrimary: true,
                                        onClick: () => selectShow('current', 'Current Show'),
                                        style: { marginTop: '12px' }
                                    }, 'Use Current Show')
                                )
                        ),
                        el('h3', { style: { marginTop: '20px', marginBottom: '0', color: attributes.headingColor } }, 'Preview Settings'),
                        el('p', { style: { fontSize: '13px', color: '#666', marginBottom: '15px' } }, 'Generated shortcode will display all selected fields with your color choices.'),
                        el('div', {
                            style: {
                                background: '#f8f8f8',
                                padding: '12px',
                                borderRadius: '4px',
                                fontFamily: 'monospace',
                                fontSize: '11px',
                                overflow: 'auto',
                                maxHeight: '120px',
                                color: '#333',
                                border: '1px solid #ddd'
                            }
                        }, shortcodeText),
                        el('div', {
                            style: {
                                marginTop: '15px',
                                padding: '12px',
                                background: `${attributes.accentColor}15`,
                                borderLeft: `4px solid ${attributes.accentColor}`,
                                borderRadius: '4px',
                                color: attributes.textColor
                            }
                        },
                            el('div', null, el('strong', { style: { color: attributes.headingColor } }, 'Selected Show: '), attributes.showTitle),
                            el('div', null, el('strong', { style: { color: attributes.headingColor } }, 'Selected Fields: '), (attributes.fieldList || []).length, ' of ', AVAILABLE_FIELDS.length),
                            attributes.fieldList?.includes('castwithbio') && el('div', null, el('strong', { style: { color: attributes.headingColor } }, 'Cast Columns: '), attributes.castcols),
                            attributes.fieldList?.includes('ticket_url') && el('div', null, el('strong', { style: { color: attributes.headingColor } }, 'Ticket Button: '), attributes.urlbutton ? `Yes (${attributes.buttonformat})` : 'No'),
                            el('div', { style: { marginTop: '10px', paddingTop: '10px', borderTop: `1px solid ${attributes.accentColor}33` } },
                                el('div', { style: { fontSize: '12px', fontWeight: '600', marginBottom: '6px', color: attributes.headingColor } }, 'Color Scheme:'),
                                el('div', { style: { display: 'flex', gap: '8px', fontSize: '11px' } },
                                    el('div', null, el('strong', null, 'Heading:')), 
                                    el('span', { style: { display: 'inline-block', width: '16px', height: '16px', background: attributes.headingColor, borderRadius: '2px', border: '1px solid #ccc' } })
                                ),
                                el('div', { style: { display: 'flex', gap: '8px', fontSize: '11px', marginTop: '4px' } },
                                    el('div', null, el('strong', null, 'Text:')), 
                                    el('span', { style: { display: 'inline-block', width: '16px', height: '16px', background: attributes.textColor, borderRadius: '2px', border: '1px solid #ccc' } })
                                ),
                                el('div', { style: { display: 'flex', gap: '8px', fontSize: '11px', marginTop: '4px' } },
                                    el('div', null, el('strong', null, 'Accent:')), 
                                    el('span', { style: { display: 'inline-block', width: '16px', height: '16px', background: attributes.accentColor, borderRadius: '2px', border: '1px solid #ccc' } })
                                )
                            )
                        )
                    )
                )
            );
        },

        save: function(props) {
            const { attributes } = props;
            const shortcode = `[tm_landingpage show_id="${attributes.showId}" field_list="${(attributes.fieldList || []).join(',')}" castcols="${attributes.castcols}" urlbutton="${attributes.urlbutton ? 'true' : 'false'}" buttonformat="${attributes.buttonformat}" text_color="${attributes.textColor}" bg_color="${attributes.backgroundColor}" accent_color="${attributes.accentColor}" heading_color="${attributes.headingColor}"]`;
            return el('div', { dangerouslySetInnerHTML: { __html: shortcode } });
        }
    });
})();
