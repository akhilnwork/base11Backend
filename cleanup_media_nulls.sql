-- Simple SQL queries to delete null values from media library
-- You can run these individually in your database management tool or via Laravel

-- 1. Delete records where essential file information is NULL
DELETE FROM media 
WHERE file_name IS NULL 
   OR name IS NULL 
   OR mime_type IS NULL 
   OR disk IS NULL;

-- 2. Delete records where essential file information is empty
DELETE FROM media 
WHERE file_name = '' 
   OR name = '' 
   OR mime_type = '' 
   OR disk = '';

-- 3. Delete records where model relationships are broken
DELETE FROM media 
WHERE model_type IS NULL 
   OR model_id IS NULL;

-- 4. Delete records where UUID is missing (required for Spatie Media Library)
DELETE FROM media 
WHERE uuid IS NULL 
   OR uuid = '';

-- 5. Delete records where file size is invalid
DELETE FROM media 
WHERE size IS NULL 
   OR size = 0;

-- 6. Comprehensive cleanup - all problematic records at once
DELETE FROM media 
WHERE file_name IS NULL OR file_name = ''
   OR name IS NULL OR name = ''
   OR mime_type IS NULL OR mime_type = ''
   OR disk IS NULL OR disk = ''
   OR model_type IS NULL
   OR model_id IS NULL
   OR uuid IS NULL OR uuid = ''
   OR size IS NULL OR size = 0;

-- 7. Check for records before cleanup (run this first to see what would be deleted)
SELECT COUNT(*) as 'Records to be deleted'
FROM media 
WHERE file_name IS NULL OR file_name = ''
   OR name IS NULL OR name = ''
   OR mime_type IS NULL OR mime_type = ''
   OR disk IS NULL OR disk = ''
   OR model_type IS NULL
   OR model_id IS NULL
   OR uuid IS NULL OR uuid = ''
   OR size IS NULL OR size = 0;
