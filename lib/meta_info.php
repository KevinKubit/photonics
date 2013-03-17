<?php
$meta_box = array();

$meta_box['publication'] = array(
	'fields' => array(
		'authors' => array(
			'name' => 'Authors',
			'desc' => 'List the authors of this paper',
			'id' => 'authors',
			'type' => 'text',
		),
		
		'publication_date' => array(
			'name' => 'Publication Date',
			'desc' => 'Month, Day, Year',
			'id' => 'publication_date',
			'type' => 'date',
		),
		'full_citation' => array(
			'name' => 'Full Citation',
			'desc' => '',
			'id' => 'full_citation',
			'type' => 'textarea',
            'tinymce' => true
		),
		'doi' => array(
			'name' => 'DOI',
			'desc' => 'Enter the digital object identifier',
			'id' => 'doi',
			'type' => 'text',
		),
		'identifier' => array(
			'name' => 'Identifier',
			'desc' => 'Enter the identifier',
			'id' => 'identifier',
			'type' => 'text',
		),
		'manuscript' => array(
			'name' => 'Manuscript',
			'desc' => 'Upload the manuscript',
			'id' => 'manuscript',
			'type' => 'file',
		),
		'bibtex' => array(
			'name' => 'BibTeX',
			'desc' => 'Upload a BibTeX file',
			'id' => 'bibtex',
			'type' => 'file',
		),
		'ris' => array(
			'name' => 'RIS',
			'desc' => 'Upload a RIS file',
			'id' => 'ris',
			'type' => 'file',
		),
		'abstract' => array(
			'name' => 'Abstract',
			'desc' => '',
			'id' => 'abstract',
			'type' => 'textarea',
		),
		'category' => array(
			'name' => 'Publication Category',
			'desc' => 'Choose a category under which to file this paper',
			'id' => 'category',
			'type' => 'dropdown',
			'options' => array('Journal Articles', 'Published Conference Proceedings', 'Conference Presentations', 'Theses', 'Miscellaneous Presentations', 'Patents')
		)
	)
);


$meta_box['lab_item'] = array(
	'fields' => array(
		'model' => array(
			'name' => 'Brand / Model',
			'desc' => 'The brand or model',
			'id' => 'model',
			'type' => 'text',
                    ),
                'description' => array(
			'name' => 'Description',
			'desc' => 'Description of the lab item',
			'id' => 'description',
			'type' => 'textarea',
                    ),
                'location' => array(
			'name' => 'Location',
			'desc' => 'Location of the lab item',
			'id' => 'location',
			'type' => 'text',
                    ),
                'serial_number' => array(
			'name' => 'Serial Number',
			'desc' => 'Serial number of the lab item',
			'id' => 'serial_number',
			'type' => 'text',
                    ),
                'inventory_number' => array(
			'name' => 'Inventory Number',
			'desc' => 'Inventory number of the lab item',
			'id' => 'inventory_number',
			'type' => 'text',
                    ),
                'manual' => array(
			'name' => 'Manual',
			'desc' => 'The manual for the lab item',
			'id' => 'manual',
			'type' => 'file',
                    ),
                'operating_procedure' => array(
			'name' => 'Operating Procedure',
			'desc' => 'The operating procedure for the lab item',
			'id' => 'operating_procedure',
			'type' => 'file',
                    )
                )
        );

$meta_box['safety_resource'] = array(
	'fields' => array(
		'sop' => array(
			'name' => 'Standard Operating Procedures',
			'desc' => '(SOP)',
			'id' => 'sop',
			'type' => 'file'
        ),
        'chp' => array(
			'name' => ' Chemical Hygiene Plans',
			'desc' => '(CHP)',
			'id' => 'chp',
			'type' => 'file'
        ),
        'msds' => array(
			'name' => 'Material Safety Data Sheets',
			'desc' => '(MSDS)',
			'id' => 'msds',
			'type' => 'file'
        )
    )
);

$meta_box['contact'] = array(
	'fields' => array(
		'address' => array(
			'name' => 'Address (Home)',
			'desc' => 'Your full home address',
			'id' => 'address',
			'type' => 'textarea',
		),
		'phone' => array(
			'name' => 'Phone (Mobile)',
			'desc' => 'Your mobile phone number',
			'id' => 'phone',
			'type' => 'text',
		),
        'phone_office' => array(
			'name' => 'Phone (Office)',
			'desc' => 'Your office phone number',
			'id' => 'phone_office',
			'type' => 'text',
		),
        'phone_home' => array(
			'name' => 'Phone (Home)',
			'desc' => 'Your home phone number',
			'id' => 'phone_home',
			'type' => 'text',
		),
		'fax' => array(
			'name' => 'Fax Number',
			'desc' => 'Your fax number',
			'id' => 'fax',
			'type' => 'text',
		),
		'email' => array(
			'name' => 'Email',
			'desc' => 'Your email address',
			'id' => 'email',
			'type' => 'text',
		),
        'url' => array(
			'name' => 'URL',
			'desc' => 'Your website address',
			'id' => 'url',
			'type' => 'text',
		),
		'office' => array(
			'name' => 'Office',
			'desc' => 'Your office location',
			'id' => 'office',
			'type' => 'text',
		),
		'degrees' => array(
			'name' => 'Degrees',
			'desc' => 'List your degrees',
			'id' => 'degrees',
			'type' => 'textarea',
		),
		'department_role' => array(
			'name' => 'Department Role',
			'desc' => 'Your role in the department. i.e professor or graduate student',
			'id' => 'department_role',
			'type' => 'dropdown',
			'options' => array('Professor', 'Graduate Student', 'Undergraduate Student', 'Alumni', 'Collaborator', 'Colleagues')
		)
	)
);
?>