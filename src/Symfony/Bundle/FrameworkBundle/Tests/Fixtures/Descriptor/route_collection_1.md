route_1
-------

- Path: /hello/{name}
- Path Regex: #^/hello(?:/(?P<name>[a-z]+))?$#s
- Host: localhost
- Host Regex: #^localhost$#si
- Scheme: http|https
- Method: GET|HEAD
- Class: Makhan\Component\Routing\Route
- Defaults: 
    - `name`: Joseph
- Requirements: 
    - `name`: [a-z]+
- Options: 
    - `compiler_class`: Makhan\Component\Routing\RouteCompiler
    - `opt1`: val1
    - `opt2`: val2


route_2
-------

- Path: /name/add
- Path Regex: #^/name/add$#s
- Host: localhost
- Host Regex: #^localhost$#si
- Scheme: http|https
- Method: PUT|POST
- Class: Makhan\Component\Routing\Route
- Defaults: NONE
- Requirements: NO CUSTOM
- Options: 
    - `compiler_class`: Makhan\Component\Routing\RouteCompiler
    - `opt1`: val1
    - `opt2`: val2

