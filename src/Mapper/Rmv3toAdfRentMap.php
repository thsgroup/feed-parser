<?php
//TODO: update adf Rent map (atm it is just a copy of adf sale map)
return array(
    'adf' =>
        array(
            'network' => array(
                'network_id' => '#_network_id#',
            ),
            'branch' => array(
                'branch_id' => '#BRANCH_ID#',
                'channel' => '#_channel#',
                'overseas' => '#_overseas#',
            ),
            'property' => array(
                'agent_ref' => '#AGENT_REF#',
                'published' => '#PUBLISHED_FLAG#',
                'property_type' => '#PROP_SUB_ID#',
                'status' => '#STATUS_ID#',
                'new_home' => '#NEW_HOME_FLAG#',
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
                    'price' => '#PRICE#',
                    'price_qualifier' => '#PRICE_QUALIFIER#',
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
                    'bedrooms' => '#BEDROOMS#',
                    'bathrooms' => '#BATHROOMS#',
                    'reception_rooms' => '#LIVING_ROOMS#',
                    'parking' => null,
                    'outside_space' => null,
                    'year_built' => null,
                    'internal_area' => '#MAX_SIZE_ENTERED#',
                    'internal_area_unit' => '#AREA_SIZE_UNIT_ID#',
                    'entrance_floor' => null,
                    'condition' => null,
                    'accessibility' => null,
                    'heating' => null,
                ),
                '@media--MEDIA_IMAGE_@' =>
                    array(
                        'media_type' => '#_media_type_image#',
                        'media_url' => '#MEDIA_IMAGE_#',
                        'caption' => '#MEDIA_IMAGE_TEXT_#',
                    ),
            ),
        ),
);
