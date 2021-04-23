export const FormatGoogleAddress = (location: any) => {
    const address: any = {};
    address.address = location.formatted_address;
    address.latitude = location.geometry.location.lat();
    address.longitude = location.geometry.location.lng();
    if (location.address_components) {
        location.address_components.forEach((component: any) => {
            if (component.types.includes('locality') || component.types.includes('administrative_area3')) {
                address.city = component.short_name;
            }
            if (component.types.includes('postal_code')) {
                address.zip_code = component.short_name;
            }
            if (component.types.includes('administrative_area_level_1')) {
                address.state = component.short_name;
            }
        });
    }
    return address;
};
