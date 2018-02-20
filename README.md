# DGCLW (Deutsche Grammophon(r) Cable Labs Writer

Simple web based utility for metadata translation.
Filtered elements from the source metadata XML file are loaded into a translation HTML form to allow for field translation.
The result form is submitted to a PHP action page which transforms the form into a target simple XML format to store translation values.

Extracted fields are :

For the asset :
- Asset ID (UMG Reference)
- Title
- Genre
- Album Cover (only available for audio assets)
- Asset Type (Audio or Video)
- Description

For each track of the asset :
- Title
- Artist
- Associated Media File
- Bitrate (for video only)
- Framerate (for video only)
- Duration
