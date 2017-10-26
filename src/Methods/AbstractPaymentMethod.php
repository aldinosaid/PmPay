<?php  
namespace PmPay\Methods;

use Plenty\Modules\Payment\Method\Contracts\PaymentMethodService;
use Plenty\Modules\Frontend\Contracts\Checkout;
use Plenty\Modules\Frontend\Session\Storage\Contracts\FrontendSessionStorageFactoryContract;
use Plenty\Plugin\Application;
use Plenty\Plugin\Log\Loggable;

use Skrill\Services\PaymentService;
/**
* 
*/
class AbstractPaymentMethod extends PaymentMethodService
{
	use Loggable;

	/**
	 * @var Checkout
	 */
	protected $checkout;

	/**
	 * @var PaymentService
	 */
	protected $paymentService;

	/**
	 * @var name
	 */
	protected $name = '';

	/**
	 * @var allowedBillingCountries
	 */

	/**
	 * @var logoFileName
	 */
	protected $logoFileName = '';

	/**
	 * @var settingsType
	 */
	protected $settingsType = 'credit-card';
	
	function __construct(Checkout $checkout, PaymentService $paymentService)
	{
		$this->checkout         = $checkout;
		$this->paymentService   = $paymentService;
		$this->paymentService->loadCurrentSettings($this->settingsType);
	}

	/**
	 * Check whether the payment setting is enabled
	 *
	 * @return bool
	 */
	protected function isEnabled()
	{
		if (array_key_exists('enabled', $this->paymentService->settings) && $this->paymentService->settings['enabled'] == 1)
		{
			return true;
		}
		return false;
	}

	/**
	 * get logo file name
	 *
	 * @return string
	 */
	protected function getLogoFileName()
	{
		return $this->logoFileName;
	}

	/**
	 * Check whether the payment method is active
	 *
	 * @return bool
	 */
	public function isActive()
	{
		if ($this->isEnabled() && $this->isShowSeparately() && $this->isBillingCountriesAllowed())
		{
			return true;
		}
		return false;
	}

	/**
	 * Get the name of the payment method
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get additional costs for Skrill.
	 * Skrill did not allow additional costs
	 *
	 * @return float
	 */
	public function getFee()
	{
		return 0.00;
	}

	/**
	 * Get the path of the icon
	 *
	 * @return string
	 */
	public function getIcon()
	{
		$app = pluginApp(Application::class);
		$icon = $app->getUrlPath('pmpay').'/images/logos/'.$this->getLogoFileName();

		return $icon;
	}

	/**
	 * Get the description of the payment method.
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return '';
	}

	/**
	 * Check if it is allowed to switch to this payment method
	 *
	 * @param int $orderId
	 * @return bool
	 */
	public function isSwitchableTo($orderId)
	{
		return false;
	}

	/**
	 * Check if it is allowed to switch from this payment method
	 *
	 * @param int $orderId
	 * @return bool
	 */
	public function isSwitchableFrom($orderId)
	{
		return true;
	}
}

?>