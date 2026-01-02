
import json
import re

def parse_municipal_data(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Split by the separator I added
    sections = content.split('_')
    
    units = []

    for section in sections:
        lines = [line.strip() for line in section.strip().split('\n') if line.strip()]
        if not lines:
            continue

        # Check if this section is just functions (continuation)
        if lines[0] == "Funciones":
            if not units:
                continue # Should not happen
            
            # Parse functions for the last unit
            current_unit = units[-1]
            # Skip "Funciones" line
            for line in lines[1:]:
                 current_unit["funciones"].append(line)
            continue

        unit = {
            "nombre": lines[0],
            "descripcion": "",
            "director": "",
            "ubicacion": "",
            "correo": [],
            "telefono": [],
            "funciones": [],
            "link": ""
        }

        # Indices to help parsing
        current_section = "descripcion"
        
        # Skip title
        i = 1
        while i < len(lines):
            line = lines[i]
            
            if line == "Director/a":
                current_section = "director"
                i += 1
                continue
            elif line == "Ubicación":
                current_section = "ubicacion"
                i += 1
                continue
            elif line == "Correo":
                current_section = "correo"
                i += 1
                continue
            elif line == "Teléfono":
                current_section = "telefono"
                i += 1
                continue
            elif line == "Funciones":
                current_section = "funciones"
                i += 1
                continue
            elif line.startswith("Información General"): 
                 i+=1
                 continue

            if current_section == "descripcion":
                if unit["descripcion"]:
                    unit["descripcion"] += " " + line
                else:
                    unit["descripcion"] = line
            elif current_section == "director":
                if unit["director"]:
                     unit["director"] += " / " + line
                else:
                    unit["director"] = line
            elif current_section == "ubicacion":
                 unit["ubicacion"] = line
            elif current_section == "correo":
                if "@" in line:
                    unit["correo"].append(line)
            elif current_section == "telefono":
                 unit["telefono"].append(line)
            elif current_section == "funciones":
                unit["funciones"].append(line)
            
            i += 1

        units.append(unit)

    # Post-process for Salud link
    for u in units:
        if u["nombre"] == "Salud":
            u["link"] = "https://apsmuniarica.cl/web/"

    return units

data = parse_municipal_data('municipal_data.txt')

with open('units.json', 'w', encoding='utf-8') as f:
    json.dump(data, f, indent=4, ensure_ascii=False)
