<?php

return array(
    'adf' => array(
        'settings' => array(
            'root' => 'adf_root',
            'data' => null,
            'new_home' => array(
                'N' => true
            )
        ),
        'adf_root' => array(
            'network' => array(
                'network_id' => '#{int}_network_id#',
            ),
            'branch' => array(
                'branch_id' => '#{int}BRANCH_ID#',
                'channel' => '#{int}_channel#',
                'overseas' => '#{bool}_overseas#',
            ),
            'property' => array(
                'agent_ref' => '#AGENT_REF#',
                'published' => '#{bool}PUBLISHED_FLAG#',
                'property_type' => '#{int}PROP_SUB_ID#',
                'status' => '#{int}STATUS_ID#',
                'new_home' => '#{bool}NEW_HOME_FLAG#',
                'create_date' => '#CREATE_DATE#',
                'update_date' => '#UPDATE_DATE#',
                'address' => array(
                    'house_name_number' => '#ADDRESS_1#',
                    'address_2' => '#ADDRESS_2#',
                    'address_3' => '#ADDRESS_3#',
                    'address_4' => '#ADDRESS_4#',
                    'town' => '#TOWN#',
                    'postcode_1' => '#POSTCODE1#',
                    'postcode_2' => '#POSTCODE2#',
                    'display_address' => '#DISPLAY_ADDRESS#',
                    'latitude' => null,
                    'longitude' => null,
                    'pov_latitude' => null,
                    'pov_longitude' => null,
                    'pov_pitch' => null,
                    'pov_heading' => null,
                    'pov_zoom' => null,
                ),
                'price_information' => array(
                    'price' => '#{double}PRICE#',
                    'price_qualifier' => '#{int}PRICE_QUALIFIER#',
                ),
                'details' => array(
                    'summary' => '#SUMMARY#',
                    'description' => '#DESCRIPTION#',
                    'features' => array(
                        '#FEATURE1#',
                        '#FEATURE2#',
                        '#FEATURE3#',
                        '#FEATURE4#',
                        '#FEATURE5#',
                        '#FEATURE6#',
                        '#FEATURE7#',
                        '#FEATURE8#',
                        '#FEATURE9#',
                        '#FEATURE10#',
                    ),
                    'bedrooms' => '#{int}BEDROOMS#',
                    'bathrooms' => '#{int}BATHROOMS#',
                    'reception_rooms' => '#{int}LIVING_ROOMS#',
                    'parking' => null,
                    'outside_space' => null,
                    'year_built' => null,
                    'internal_area' => '#{int}MAX_SIZE_ENTERED#',
                    'internal_area_unit' => '#{int}AREA_SIZE_UNIT_ID#',
                    'entrance_floor' => null,
                    'condition' => null,
                    'accessibility' => null,
                    'heating' => null,
                ),
                '@media--MEDIA_IMAGE_@' => array(
                    'media_type' => '#{int}_media_type_image#',
                    'media_url' => '#MEDIA_IMAGE_#',
                    'caption' => '#MEDIA_IMAGE_TEXT_#',
                ),
            ),
        ),
    )
);
