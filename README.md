# 会員グループ管理::会員ランク管理アドオン for EC-CUBE4

本プラグインは[会員グループ管理プラグイン](https://www.ec-cube.net/products/detail.php?product_id=2255) をインストール・有効化する必要があります。

会員グループ管理::会員ランク管理アドオンは、購入金額や購入回数に応じて会員グループを自動登録することができるプラグインです。

ゴールド会員、シルバー会員、ブロンズ会員などの会員グループを登録してそれぞれに購入金額と購入回数を登録しておくと、会員がログインしたときに条件にマッチした会員グループが会員に登録されます。  
条件にマッチした会員グループが複数あった場合、優先度が最上位の会員グループが登録されます。  

会員グループの優先度は会員グループ一覧ページで設定できます。設定はドラッグアンドドロップでできます。

# 設定方法
+ 会員グループを作成
+ 会員グループに購入回数と購入金額を設定

# 会員に会員グループが自動登録されるタイミング
+ ログインに条件にマッチした会員グループが会員に登録されます。

## ランク昇格条件のカスタマイズ方法

本プラグインはランク昇格（※会員グループの自動登録）条件を変更することができます。
デフォルトの条件は購入金額と購入回数ですが、例えば会員の最終購入日から1ヶ月過ぎていたら会員グループから場外するといったことも可能です。

Customizeディレクトリに以下の実装を行う必要があります。
+ RankInterfaceを実装したランク決定クラス。
+ services.yamlにRankInterfaceを実装したクラスを設定。priorityを1以上にすると反映されます。


### ランク決定クラスの実装例

```php
namespace Plugin\CustomerGroupRank\Service\Rank;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Plugin\CustomerGroup\Entity\Group;

class Rank implements RankInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 優先度が最上位のグループを会員に設定する
     *
     * @param Customer $customer
     */
    public function decide(Customer $customer): void
    {
        $groups = $this->getGroups($customer);
        $groups = new ArrayCollection($groups);

        if ($groups->count() > 0) {
            /** @var Group $group */
            $group = $groups->first();
            if ($customer->getGroups()->count() > 0) {
                foreach ($customer->getGroups() as $originGroup) {
                    $customer->removeGroup($originGroup);
                }
            }
            $customer->addGroup($group);
            $group->addCustomer($customer);
            $this->entityManager->flush();
        }
    }

    /**
     * 会員に適用可能なグループ一覧を取得
     *
     * @param Customer $customer
     * @return array
     */
    protected function getGroups(Customer $customer): array
    {
        $searchData = [
            'buyTimes' => $customer->getBuyTimes(),
            'buyTotal' => $customer->getBuyTimes()
        ];
        return $this->entityManager->getRepository(Group::class)->getQueryBuilderBySearchData($searchData)
            ->getQuery()
            ->getResult();
    }
}
```

### services.yamlの設定例

```yaml
services:
  Plugin\CustomerGroupRank\Service\Rank\Rank:
    - { name: 'plugin.customer.group.rank', priority: 1 }
```


カスタマイズ、または他社プラグインとの競合による動作不良つきましてはサポート対象外です。

本プラグインを導入したことによる不具合や被った不利益につきましては一切責任を負いません。
ご理解の程よろしくお願いいたします。
