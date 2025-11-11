<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Inventory Alert</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px;">
        <h2 style="color: #dc3545; margin-top: 0;">Low Inventory Alert</h2>
        <p style="margin: 0;">A product has reached its low inventory threshold.</p>
    </div>

    <div style="background-color: #fff; padding: 20px; border: 1px solid #dee2e6; border-radius: 5px;">
        <h3 style="color: #333; margin-top: 0;">Product Details</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; font-weight: bold; width: 150px;">Product Name:</td>
                <td style="padding: 8px 0;">{{ $product->name }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Product ID:</td>
                <td style="padding: 8px 0;">#{{ $product->id }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Current Quantity:</td>
                <td style="padding: 8px 0; color: #dc3545; font-weight: bold;">{{ $currentQuantity }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Low Inventory Threshold:</td>
                <td style="padding: 8px 0;">{{ $threshold }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; font-weight: bold;">Inventory Type:</td>
                <td style="padding: 8px 0;">{{ ucfirst($product->inventory_type) }}</td>
            </tr>
        </table>

        <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px;">
            <p style="margin: 0; color: #856404;">
                <strong>Action Required:</strong> Please review the inventory for this product and restock if necessary.
            </p>
        </div>
    </div>

    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; text-align: center;">
        <p style="margin: 0; color: #6c757d; font-size: 12px;">
            This is an automated notification from your e-commerce system.
        </p>
    </div>
</body>
</html>

