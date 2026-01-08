# Multi-Hotel Implementation Guide

## Current State

**The system is currently designed for a SINGLE hotel.** There is no Hotel model or hotel scoping in place. All data (rooms, bookings, guests, users) is shared across the entire system.

## What Multi-Hotel Support Would Require

To make this system support multiple hotels, you would need to implement **multi-tenancy**. Here are the main approaches:

### Approach 1: Shared Database with Hotel ID (Recommended for this project)

**Pros:**
- Easier to implement
- Single database to manage
- Shared guests across hotels (if desired)
- Lower infrastructure costs

**Cons:**
- All hotels share the same database
- Requires careful data isolation
- More complex queries (always filtering by hotel_id)

**What needs to change:**

1. **Create Hotel Model & Migration**
   ```php
   // hotels table
   - id
   - name
   - address
   - phone
   - email
   - logo (image)
   - settings (JSON)
   - created_at, updated_at
   ```

2. **Add `hotel_id` to all relevant tables:**
   - `rooms` → `hotel_id`
   - `room_types` → `hotel_id`
   - `bookings` → `hotel_id` (via room)
   - `users` → `hotel_id` (staff belong to specific hotel)
   - `guests` → Can be shared OR per-hotel (your choice)

3. **Update Models:**
   - Add `hotel_id` to fillable arrays
   - Add `belongsTo(Hotel::class)` relationships
   - Add global scopes to filter by hotel

4. **Update Controllers & Resources:**
   - Filter all queries by current hotel
   - Add hotel selection/switching mechanism
   - Update Filament resources to scope by hotel

5. **Add Hotel Management:**
   - Super Admin role (manages all hotels)
   - Hotel Admin role (manages one hotel)
   - Hotel selection/switching UI

6. **Update Guest Portal:**
   - Guests select hotel when booking
   - Or separate guest portals per hotel (subdomain approach)

### Approach 2: Separate Databases (Tenancy Package)

**Pros:**
- Complete data isolation
- Better security
- Can scale independently

**Cons:**
- More complex setup
- Requires Laravel Tenancy package (e.g., stancl/tenancy)
- More infrastructure overhead

### Approach 3: Subdomain-Based Multi-Tenancy

**Pros:**
- Each hotel gets its own subdomain (hotel1.example.com)
- Clear separation
- Can use shared or separate databases

**Cons:**
- Requires DNS configuration
- More complex routing
- SSL certificates for each subdomain

## Recommended Implementation Plan (Approach 1)

If you want to proceed with multi-hotel support, here's what needs to be done:

### Phase 1: Database Structure
1. Create `hotels` table
2. Add `hotel_id` to: rooms, room_types, users, bookings
3. Create migrations for all changes
4. Update existing data (assign to default hotel)

### Phase 2: Models & Relationships
1. Create `Hotel` model
2. Update all models with `hotel_id` relationships
3. Add global scopes for hotel filtering
4. Update model relationships

### Phase 3: Authentication & Authorization
1. Add hotel selection to user login
2. Store current hotel in session
3. Update policies to check hotel access
4. Add super admin role

### Phase 4: Filament Resources
1. Update all Filament resources to filter by hotel
2. Add Hotel resource for management
3. Add hotel selector widget
4. Update all queries to include hotel scope

### Phase 5: Guest Portal
1. Add hotel selection to booking flow
2. Update room availability queries
3. Show hotel-specific information
4. Update email templates with hotel branding

### Phase 6: Middleware & Scoping
1. Create `SetHotel` middleware
2. Auto-detect hotel from domain/subdomain (optional)
3. Ensure all queries are scoped
4. Add hotel context to all views

## Key Considerations

### Guest Management
- **Option A:** Guests are shared across hotels (one guest can book at multiple hotels)
- **Option B:** Guests are per-hotel (separate guest accounts per hotel)

**Recommendation:** Option A (shared guests) for better UX and loyalty program integration.

### User Management
- Staff users belong to specific hotels
- Super admins can access all hotels
- Hotel admins can only manage their hotel

### Loyalty Points
- **Option A:** Points are per-hotel
- **Option B:** Points are shared across hotel chain

**Recommendation:** Option B for chain hotels, Option A for independent hotels.

### Room Numbers
- Room numbers can be unique per hotel (101 at Hotel A, 101 at Hotel B)
- Or globally unique (requires hotel_id + room_number composite)

## Estimated Effort

- **Small changes:** 2-3 days (basic hotel_id scoping)
- **Full implementation:** 1-2 weeks (complete multi-hotel with all features)
- **Production-ready:** 2-3 weeks (testing, edge cases, documentation)

## Quick Start (If You Want to Proceed)

I can help you implement this step by step. The basic structure would be:

1. Create Hotel model and migration
2. Add hotel_id to existing tables
3. Create hotel scoping middleware
4. Update one resource as an example (RoomResource)
5. Add hotel management UI

Would you like me to start implementing multi-hotel support?

## Alternative: Keep Single Hotel

If you only need to manage one hotel, the current system is perfectly fine. Multi-hotel adds complexity that may not be necessary if:
- You're managing a single property
- You don't plan to expand
- The added complexity isn't worth it

Let me know if you'd like me to proceed with multi-hotel implementation or if you have questions about the approach!

