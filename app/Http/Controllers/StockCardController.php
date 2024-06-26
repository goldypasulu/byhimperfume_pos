<?php

namespace App\Http\Controllers;

use App\Models\StockCard;
use App\Models\Product;
use App\Models\Fragrance;
use App\Models\CurrentStock;
use App\Models\TransactionItem;
use App\Models\Branch;
use Illuminate\Http\Request;

class StockCardController extends Controller
{
    // index
    public function index(Request $request)
    {
        if (empty($request->branch_id)) {
            $data = StockCard::with('product', 'branch', 'fragrance')->get();
        } else {
            $data = StockCard::with('product', 'branch', 'fragrance')
                            ->where('branch_id', $request->branch_id)
                            ->get();
        }

        $branches = Branch::all();
        return view('pages.stockCard.index', compact('data', 'branches'));
    }


    public function opname($id)
    {
        $stockCard = StockCard::with('product', 'branch', 'fragrance')->findOrFail($id);
        return view('pages.stockCard.opname', compact('stockCard'));
    }

    public function update(Request $request, $id)
    {
        $stockCard = StockCard::findOrFail($id);
        // Retrieve fragrance data
        // Ambil data fragrance berdasarkan product_id
        $fragrance = Fragrance::where('product_id', $request->product_id)->first();
        if (!$fragrance) {
            return response()->json(['message' => 'Fragrance not found'], 404);
        }

        $gram_to_ml = $fragrance->gram_to_ml;
        $ml_to_gram = $fragrance->ml_to_gram;

        $currentStock = CurrentStock::where('product_id', $request->product_id)->first();

        $newStockCard = new StockCard();
        $newStockCard->product_id = $request->product_id;
        $newStockCard->branch_id = $stockCard->branch_id;
        $newStockCard->fragrance_id = $fragrance->id;

        // Update stock opname dates
        $newStockCard->stock_opname_date = $request->stock_opname_date;

        // Retrieve the previous stock opname to get the real_g value
        $previousStockOpname = StockCard::where('product_id', $request->product_id)
                                        ->where('branch_id', $stockCard->branch_id)
                                        ->where('id', '<', $id)
                                        ->orderBy('id', 'desc')
                                        ->first();

        if ($previousStockOpname) {
            $previousStockOpnameDate = $previousStockOpname->stock_opname_date;
        } else {
            $previousStockOpnameDate = null;
        }

        // Set opening stock gram to the real stock gram of the previous opname, or 0 if none exists
        $newStockCard->opening_stock_gram = $previousStockOpname ? $previousStockOpname->real_g : 0;

        // Retrieve transaction items within the opname date range
        $transactionItems = TransactionItem::where('product_id', $request->product_id)
                                            ->when($previousStockOpnameDate, function ($query) use ($previousStockOpnameDate, $request) {
                                                $query->whereBetween('created_at', [$previousStockOpnameDate, $request->stock_opname_date]);
                                            })
                                            ->get();

        // Calculate sales (ml)
        $sales_ml = $transactionItems->sum('quantity'); // Assuming quantity is in ml
        $newStockCard->sales_ml = $sales_ml;

        // Save real stock gram if provided
        if ($request->has('real_stock_gram')) {
            $newStockCard->real_g = $request->real_stock_gram - ($fragrance->bottle_weight - $fragrance->pump_weight);
            $newStockCard->real_ml = $newStockCard -> real_g * $gram_to_ml;
            $currentStock->current_stock_gram = $newStockCard->real_g;
            $currentStock->current_stock = $newStockCard->real_ml;
        }

        $newStockCard->difference_g = $newStockCard->real_g - $newStockCard->calc_g;
        $newStockCard->difference_ml = $newStockCard->real_ml - $newStockCard->calc_ml;

        dd('previous stock opname:', $previousStockOpname, 'transaction item :', $transactionItems, $newStockCard, $currentStock);

        $newStockCard->save();

        return redirect()->route('stockcard.index')->with('success', 'Stock opname updated successfully.');
    }
}
