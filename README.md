Recycling Facility Directory – Laravel Project

This is a small Laravel application that manages a Recycling Facility Directory stored in a MySQL database.

🚀 Features

User authentication (only logged-in users can access facilities).

Add, edit, delete recycling facilities via a web form.

Paginated table with:

Search by business name, city, or material.

Filter by materials accepted.

Sort by last update date.

Facility detail page showing:

All facility information

Materials accepted

Google Maps embed based on address

Export filtered results as CSV

🗄️ Database Design
Tables

facilities

id (PK)

business_name (string)

last_update_date (date)

street_address (string)

city (string)

state (string)

postal_code (string)

materials

id (PK)

name (string)

facility_material (pivot table – many-to-many relationship)

facility_id (FK → facilities.id)

material_id (FK → materials.id)

Relationships

Facility has many Material (many-to-many).

Material belongs to many Facility.

🔍 Search, Filter, Sort, Export

Search:
Implemented using where and orWhereHas in the FacilityController.

if ($request->filled('search')) {
    $search = $request->input('search');
    $query->where(function ($q) use ($search) {
        $q->where('business_name', 'like', "%{$search}%")
->orWhere('city', 'like', "%{$search}%")
          ->orWhereHas('materials', function ($mq) use ($search) {
              $mq->where('materials.name', 'like', "%{$search}%");
});
});
}

Filter by Material:
Added a dropdown with available materials.
Uses whereHas on pivot relationship to return only facilities that accept the chosen material.

Sort by Date:
Implemented with orderBy('last_update_date', 'desc').

Export CSV:
Added an export route to download currently filtered results as a CSV file using fputcsv.
