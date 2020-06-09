# Setup

The setup page is where the Active Directory connection is made. 

The `Name` is not currently used elsewhere, but may have a place.

`Abbreviation` is much like `Name` in not having a home away from home.

The password is encrypted with a 2048-bit AES-256 encryption standard for maximum security.
  
!!! note "Important"
    In order to change user passwords, the connection must be made securly. LDAPS is un-tested,
    but TLS is available and fully vetted.
    
!!! note "Permission Check"
    Make sure to perform a permission check after receiving a green signal from
    the AD Connection Test. This will ensure that the user configured has at least permissions to the Base DN.