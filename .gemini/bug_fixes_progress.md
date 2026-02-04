# Fire Safety Bug Fixes - Part 2

## Status: IN PROGRESS

### Multiple Pages
1. ✅ **SweetAlert2 Integration** - Added to dashboard.blade.php
2. ⏳ **Replace all alert() with Swal.fire()** - Need to find and replace all instances

### Building Page
1. ⏳ **Update Functionality** - Needs comprehensive validation
   - A. Building Code update with confirmation
   - B. Floor reduction with cascading deletions
   - C. Room reduction with extinguisher reassignment
   - D. Emergency exits, description, safety features update
   - E. Building type locked (already implemented)
2. ✅ **Add "Two Stairways" to safety features** - Already in buildings.blade.php line 723-725
3. ✅ **Gymnasium & Cafeteria restrictions** - Already implemented in controller

### Alarm System Page
1. ✅ **Building display with name** - Already shows "BLDG-CODE (Building Name)"

### Fire Extinguisher Page
1. ⏳ **Total rooms counting** - Need to investigate
2. ⏳ **Minimum 1 room per floor** - Need to implement
3. ⏳ **Pressure validation by status** - Need to implement
4. ⏳ **Add "Inspect & Update" button** - Need to add action column

## Next Steps
1. Check buildings.blade.php update functionality
2. Fix fire extinguisher room counting
3. Add pressure validation
4. Add inspect & update button
